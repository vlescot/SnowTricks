<?php
declare(strict_types=1);

namespace App\Tests\EndToEnd;

use App\Domain\DTO\GroupDTO;
use App\Domain\DTO\PictureDTO;
use App\Domain\DTO\TrickDTO;
use App\Domain\DTO\VideoDTO;
use App\Domain\Entity\Group;
use App\Tests\ModalConnectionTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Panther\PantherTestCase;

final class UpdateTrickTest extends PantherTestCase
{
    use ModalConnectionTrait;


    public function testResponseWithAnonUSer()
    {
        $client = parent::createClient();
        $client->request('GET', '/spins/modifier');


        static::assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());

        $crawler = $client->followRedirect();
        $h3 = $crawler->filter('h3')->text();

        static::assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        static::assertSame('Connexion', $h3);
    }


    public function testPageWithLoggedUser()
    {
        $client = parent::createClient([], [
            'PHP_AUTH_USER' => 'root',
            'PHP_AUTH_PW'   => 'root',
        ]);

        $crawler = $client->request('GET', '/spins/modifier');

        $nbFormOnPage = $crawler->filter('form')->count();
        $h1 = $crawler->filter('h1')->text();

        static::assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        static::assertSame('Modifier Spins', $h1);
        static::assertSame(1, $nbFormOnPage);
    }


    public function testUserGetUpdateTrickPage()
    {
        $client = parent::createPantherClient('127.0.0.1', 8999);
        $crawler = $client->request('GET', '/');

        // Checks if the user is visiting the home page.
        $h1 = $crawler->filter('h1')->text();
        static::assertSame('SnowTricks', $h1);

        $link = $crawler->filter('.main-trick')->last()->filter('a')->link();
        $crawler = $client->click($link);


        // Checks if user is visiting the trick page
        static::assertSame(Response::HTTP_OK, $client->getResponse()->getStatus());

        $crawler = $this->getUserConnection('root', 'root', $client);

        $client->waitFor('#flash-container');
        $flashMessage = $crawler->filter('#flash-container strong')->text();

        // Checks if the user is connected
        static::assertSame('Bonjour root', $flashMessage);

        $crawler->selectButton('Modifie cette figure')->click();

        // Checks if user get the page in route /japan/modifier
        static::assertSame(Response::HTTP_OK, $client->getResponse()->getStatus());
    }


    /**
     * @param TrickDTO $trickDTO
     *
     * @dataProvider provideData
     */
    public function testUserUpdateTrick(TrickDTO $trickDTO)
    {
        $client = parent::createClient([], [
            'PHP_AUTH_USER' => 'root',
            'PHP_AUTH_PW'   => 'root',
        ]);

        $crawler = $client->request('POST', '/spins/modifier');

        $form = $crawler->selectButton('Enregistrer')->form();
        $values = $form->getPhpValues();

        $values['update_trick']['title'] = $trickDTO->title;
        $values['update_trick']['description'] = $trickDTO->description;
        $values['update_trick']['mainPicture']['file'] = $trickDTO->mainPicture->file;

        foreach ($trickDTO->pictures as $key => $pictureDTO) {
            $values['update_trick']['pictures'][$key]['file'] = $pictureDTO->file;
        }
        foreach ($trickDTO->videos as $key => $videoDTO) {
            $values['update_trick']['videos'][$key]['iFrame'] = $videoDTO->iFrame;
        }
        foreach ($trickDTO->newGroups as $key => $groupDTO) {
            $values['update_trick']['newGroups'][$key]['name'] = $groupDTO->name;
        }

        $client->request($form->getMethod(), $form->getUri(), $values, $form->getPhpFiles());

        if ($client->getResponse()->getStatusCode() === 200) {
            $err = $crawler->filter('.form-error-message')->text();
            dump($err);
        }

        static::assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());

        $crawler = $client->followRedirect();
        $h1 = $crawler->filter('h1')->text();

        static::assertSame($trickDTO->title, $h1);
        static::assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }


    /**
     * @return \Generator
     */
    public function provideData()
    {
        parent::bootKernel();
        $imagesTestFolder = $this::$kernel->getRootDir() . '/../public/image/tests/';


        $title = 'Updated Title';
        $description = 'Updated Description';
        $mainPictureDTO = new PictureDTO(new File($imagesTestFolder . 'b1.png'));

        $picturesDTO = [
            new PictureDTO(new File($imagesTestFolder . 'r1.png')),
            new PictureDTO(new File($imagesTestFolder . 'r2.png')),
            new PictureDTO(new File($imagesTestFolder . 'r3.png'))
        ];

        $videosDTO = [
            new VideoDTO('<iframe src="https://www.youtube.com/embed/1"></iframe>'),
            new VideoDTO('<iframe src="https://www.youtube.com/embed/2"></iframe>'),
            new VideoDTO('<iframe src="https://www.youtube.com/embed/3"></iframe>'),
        ];

        $groups = new ArrayCollection();
        $groups->add(new Group('Jump'));
        $groups->add(new Group('Rotation'));

        $newGroupsDTO = [
            new GroupDTO('New Group'),
            new GroupDTO('Another New Group'),
        ];


        yield [
            new TrickDTO(
                $title,
                $description,
                $mainPictureDTO,
                $picturesDTO,
                $videosDTO,
                $groups,
                $newGroupsDTO
            )
        ];
    }
}
