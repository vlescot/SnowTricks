<?php
declare(strict_types=1);

namespace App\Tests\EndToEnd;

use App\Domain\DTO\GroupDTO;
use App\Domain\DTO\Interfaces\TrickDTOInterface;
use App\Domain\DTO\PictureDTO;
use App\Domain\DTO\TrickDTO;
use App\Domain\DTO\VideoDTO;
use App\Domain\Entity\Group;
use App\Tests\ModalConnectionTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Panther\PantherTestCase;

final class CreateTrickTest extends PantherTestCase
{
    use ModalConnectionTrait;


    public function testResponseWithAnonUSer()
    {
        $client = parent::createClient();
        $client->request('GET', '/figure/creer');


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

        $crawler = $client->request('GET', '/figure/creer');

        static::assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $nbFormOnPage = $crawler->filter('form')->count();
        $h1 = $crawler->filter('h1')->text();

        static::assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        static::assertSame('Nouveau Trick', $h1);
        static::assertEquals(1, $nbFormOnPage);
    }


    public function testUserGetCreateTrickPage()
    {
        $client = static::createPantherClient('127.0.0.1', 8999);
        $crawler = $client->request('GET', '/');

        // Checks if the user is visiting the home page.
        $h1 = $crawler->filter('h1')->text();
        static::assertSame('SnowTricks', $h1);

        $crawler->filter('nav .nav-item:nth-child(1) span')->click();
        $client->waitFor('.modal');

        $connectionForm = $crawler->selectButton('Se connecter')->form();
        $connectionForm['login[_login]'] = 'root';
        $connectionForm['login[_password]'] = 'root';

        $crawler = $client->submit($connectionForm);

        $client->waitFor('#flash-container');
        $flashMessage = $crawler->filter('#flash-container strong')->text();

        // Checks if the user is connected
        static::assertSame(Response::HTTP_OK, $client->getResponse()->getStatus());
        static::assertSame('Bonjour root', $flashMessage);

        $crawler->selectLink('Créer un Trick')->click();

        // Checks if user get the page in route /figure/creer
        static::assertSame(Response::HTTP_OK, $client->getResponse()->getStatus());
    }

    /**
     * @param TrickDTOInterface $trickDTO
     *
     * @dataProvider provideData
     */
    public function testUserCreateTrick(TrickDTOInterface $trickDTO)
    {
        $client = parent::createClient([], [
            'PHP_AUTH_USER' => 'root',
            'PHP_AUTH_PW'   => 'root',
        ]);

        $crawler = $client->request('POST', '/figure/creer');

        $form = $crawler->selectButton('Enregistrer')->form();
        $values = $form->getPhpValues();

        $values['create_trick']['title'] = $trickDTO->title;
        $values['create_trick']['description'] = $trickDTO->description;
        $values['create_trick']['mainPicture']['file'] = $trickDTO->mainPicture->file;

        foreach ($trickDTO->pictures as $key => $pictureDTO) {
            $values['create_trick']['pictures'][$key]['file'] = $pictureDTO->file;
        }
        foreach ($trickDTO->videos as $key => $videoDTO) {
            $values['create_trick']['videos'][$key]['iFrame'] = $videoDTO->iFrame;
        }
        foreach ($trickDTO->newGroups as $key => $groupDTO) {
            $values['create_trick']['newGroups'][$key]['name'] = $groupDTO->name;
        }

        $client->request($form->getMethod(), $form->getUri(), $values, $form->getPhpFiles());

        static::assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());

        $crawler = $client->followRedirect();
        $h1 = $crawler->filter('h1')->text();

        static::assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        static::assertSame($trickDTO->title, $h1);
    }


    /**
     * @return \Generator
     */
    public function provideData()
    {
        parent::bootKernel();
        $imagesTestFolder = $this::$kernel->getRootDir() . '/../public/image/tests/';


        $title = 'New Title';
        $description = 'New Description';
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
