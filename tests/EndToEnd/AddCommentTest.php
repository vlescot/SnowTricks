<?php
declare(strict_types=1);

namespace App\Tests\EndToEnd;

use App\Tests\ModalConnectionTrait;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Panther\PantherTestCase;

final class AddCommentTest extends PantherTestCase
{
    use ModalConnectionTrait;


    public function testUserGetTrickPage()
    {
        $client = parent::createClient();
        $crawler = $client->request('GET', '/');

        // Checks if the user is visiting the home page.
        $h1 = $crawler->filter('h1')->text();
        static::assertSame('SnowTricks', $h1);

//        $link = $crawler->filter('.main-trick')->first()->filter('a')->link();


        $link = $crawler->filter('.main-trick a')->link();

        $crawler = $client->click($link);

        $trickDescription = $crawler->filter('.trick-description');

        // Checks if user is visiting the trick page
        static::assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        static::assertNotNull($trickDescription);
    }

    public function testUserSubmitComment()
    {
        $newComment = 'This is a Test Comment';


        $client = static::createPantherClient('127.0.0.1', 8999);
        $client->request('GET', '/japan');

        $crawler = $this->getUserConnection('root', 'root', $client);

        $form = $crawler->selectButton('Poster')->form();
        $form['comment[content]'] = $newComment;

        $crawler = $client->submit($form);

        $commentContent = $crawler->filter('.comment')->first()->text();

        static::assertSame($newComment, $commentContent);
    }
}
