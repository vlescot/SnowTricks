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
        $client->request('GET', '/');

        static::assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }
}
