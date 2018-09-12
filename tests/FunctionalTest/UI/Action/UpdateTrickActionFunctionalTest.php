<?php
declare(strict_types=1);

namespace App\Tests\FunctionalTest\UI\Action;

use App\Domain\DTO\GroupDTO;
use App\Domain\DTO\PictureDTO;
use App\Domain\DTO\TrickDTO;
use App\Domain\DTO\VideoDTO;
use App\Domain\Entity\Group;
use App\Domain\Entity\Picture;
use App\Domain\Entity\Trick;
use App\Domain\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Response;

final class UpdateTrickActionFunctionalTest extends WebTestCase
{
    public function testResponseWithAnonUSer()
    {
        $client = static::createClient();
        $client->request('GET', '/spins/modifier');


        static::assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());

        $crawler = $client->followRedirect();
        $h3 = $crawler->filter('h3')->text();

        static::assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        static::assertSame('Connexion', $h3);
    }

    // TODO
    public function testPageWithLoggedUser()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'root',
            'PHP_AUTH_PW'   => 'root',
        ]);

        $crawler = $client->request('GET', '/spins/modifier');

        $nbFormOnPage = $crawler->filter('form')->count();
        $h1 = $crawler->filter('.exception-message')->text();
        dump($h1);
        static::assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
//        static::assertSame('Modifier Spins', $h1);
//        static::assertEquals(1, $nbFormOnPage);
    }



    /**
     * @param TrickDTO $trickDTO
     *
     * @dataProvider provideData
     */
    // TODO
//    public function testWithTrickCreation(TrickDTO $trickDTO)
//    {
//    }


    /**
     * @return \Generator
     *
     * @throws \Exception
     */
    public function provideData()
    {
        static::bootKernel();
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


        $user = new User();
        $user->registration('NewUser', 'newmail@mail.com', 'azerty00');
        $oldMainPicture = new Picture('image/tests/b1.png', 'b1.png', 'image-b1');


        yield [
            new Trick(
                'Old Title',
                'Old Description',
                $user,
                $oldMainPicture
            ),
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
