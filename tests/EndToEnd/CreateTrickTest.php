<?php
declare(strict_types=1);

namespace App\Tests\EndToEnd;

use App\Domain\DTO\GroupDTO;
use App\Domain\DTO\Interfaces\TrickDTOInterface;
use App\Domain\DTO\PictureDTO;
use App\Domain\DTO\TrickDTO;
use App\Domain\DTO\VideoDTO;
use App\Domain\Entity\Group;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Response;

final class CreateTrickTest extends WebTestCase
{
    public function testResponseWithAnonUSer()
    {
        $client = static::createClient();
        $client->request('GET', '/figure/creer');


        static::assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());

        $crawler = $client->followRedirect();
        $h3 = $crawler->filter('h3')->text();

        static::assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        static::assertSame('Connexion', $h3);
    }


    public function testPageWithLoggedUser()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'root',
            'PHP_AUTH_PW'   => 'root',
        ]);

        $crawler = $client->request('GET', '/figure/creer');

        static::assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $nbFormOnPage = $crawler->filter('form')->count();
        $h1 = $crawler->filter('h1')->text();

        static::assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        static::assertSame('Ajouter une figure', $h1);
        static::assertEquals(1, $nbFormOnPage);
    }


    /**
     * @param TrickDTOInterface $trickDTO
     *
     * @dataProvider provideData
     */
    public function testUserCreateTrick(TrickDTOInterface $trickDTO)
    {
        $client = static::createClient([], [
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

        $crawler = $client->request($form->getMethod(), $form->getUri(), $values, $form->getPhpFiles());
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
        static::bootKernel();
        $imagesTestFolder = $this::$kernel->getRootDir() . '/../public/image/tests/';


        $title = 'New Title';
        $description = 'New Description';
        $mainPictureDTO = new PictureDTO(new File($imagesTestFolder . 'b11.png'));

        $picturesDTO = [
            new PictureDTO(new File($imagesTestFolder . 'b2.png')),
            new PictureDTO(new File($imagesTestFolder . 'b3.png')),
            new PictureDTO(new File($imagesTestFolder . 'b4.png')),
        ];

        $videosDTO = [
            new VideoDTO('<iframe src="https://www.youtube.com/embed/1"></iframe>'),
            new VideoDTO('<iframe src="https://www.youtube.com/embed/2"></iframe>'),
            new VideoDTO('<iframe src="https://www.youtube.com/embed/3"></iframe>'),
        ];

        $groups = new ArrayCollection();
        $groups->add(new Group('Jump'. rand(0, 99)));
        $groups->add(new Group('Rotation'. rand(0, 99)));

        $newGroupsDTO = [
            new GroupDTO('testCreateTrickNewGroup1'),
            new GroupDTO('testCreateTrickNewGroup2'),
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
