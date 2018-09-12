<?php
declare(strict_types=1);

namespace App\Tests\FunctionalTest\UI\Action;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class TrickPageActionFunctionalTest extends WebTestCase
{
    public function testResponseWithAnonUser()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/spins');

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

        $crawler = $client->request('GET', '/spins');

        $nbFormOnPage = $crawler->filter('form')->count();

        static::assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        static::assertEquals(1, $nbFormOnPage);
    }

    public function testPageWithCommentAdding()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'root',
            'PHP_AUTH_PW'   => 'root',
        ]);

        $crawler = $client->request('POST', '/spins');

        $form = $crawler->selectButton('Poster')->form();
        $form['comment[content]'] = 'A test comment';
        $client->submit($form);

        static::assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }
}
