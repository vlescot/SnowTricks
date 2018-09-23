<?php
declare(strict_types=1);

namespace App\Tests\EndToEnd;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Panther\PantherTestCase;

final class TrickPageTest extends PantherTestCase
{
    use ModalUserHelperTrait;


    public function testResponseWithAnonUser()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/mute');

        $nbFormOnPage = $crawler->filter('form')->count();

        static::assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        static::assertEquals(0, $nbFormOnPage);
    }

    public function testPageWithLoggedUser()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'root',
            'PHP_AUTH_PW'   => 'root',
        ]);

        $crawler = $client->request('GET', '/mute');

        $nbFormOnPage = $crawler->filter('form')->count();

        static::assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        static::assertEquals(1, $nbFormOnPage);
    }

    public function testUserGetTrickPage()
    {
        // Checks if the user is visiting the home page.
        $client = parent::createClient();
        $crawler = $client->request('GET', '/');

        $h1 = $crawler->filter('h1')->text();
        static::assertSame('SnowTricks', $h1);

        // Checks if user is visiting the trick page
        $link = $crawler->filter('.main-trick a')->link();
        $crawler = $client->click($link);

        $trickDescription = $crawler->filter('.trick-description');

        static::assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        static::assertNotNull($trickDescription);
    }

    public function testUserSubmitComment()
    {
        $newComment = 'This is a Test Comment';

        $client = static::createPantherClient('127.0.0.1', 8999);
        $client->request('GET', '/mute');
        $client->waitFor('h1');

        $crawler = $this->getUserConnection('root', 'root', $client);
        $client->waitFor('h1');

        $form = $crawler->selectButton('Poster')->form();
        $form['comment[content]'] = $newComment;

        $crawler = $client->submit($form);
        $client->waitFor('h1');

        $commentContent = $crawler->filter('.comment')->first()->text();

        static::assertSame($newComment, $commentContent);

        $client->quit();
    }
}
