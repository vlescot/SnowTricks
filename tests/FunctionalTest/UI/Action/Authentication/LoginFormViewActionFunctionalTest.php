<?php
declare(strict_types=1);

namespace App\Tests\FunctionalTest\UI\Action\Authentication;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class LoginFormViewActionFunctionalTest extends WebTestCase
{
    public function testReturnGoodContent()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/connexion');

        $h1 = $crawler->filter('h3')->text();
        $nbForm = $crawler->filter('form')->count();

        static::assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        static::assertSame('Connexion', $h1);
        static::assertEquals(1, $nbForm);
    }
}
