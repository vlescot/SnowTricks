<?php
declare(strict_types=1);

namespace App\Tests\FunctionalTest\UI\Action;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class HomeActionFunctionalTest extends WebTestCase
{
    public function testHomePageStatusCode()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $h1 = $crawler->filter('h1')->text();

        static::assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        static::assertSame('SnowTricks', $h1);
    }
}
