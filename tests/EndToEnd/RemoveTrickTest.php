<?php
declare(strict_types=1);

namespace App\Tests\EndToEnd;

use Symfony\Component\Panther\PantherTestCase;

final class RemoveTrickTest extends PantherTestCase
{
    use ModalUserHelperTrait;


    public function testRemoveTrick()
    {
        $client = static::createPantherClient('127.0.0.1', 8999);
        $client->request('GET', '/');

        $crawler = $this->getUserConnection('root', 'root', $client);

        $oldNbTrick = $crawler->filter('.main-trick')->count();

        $crawler->filter('a[title="Close Toolbar"]')->click(); // Hide Symfony toolbar prevents errors

        $crawler->filter('.main-trick:first-child .fa-trash-alt')->parents()->click();
        $client->waitFor('.modal');
        $crawler->filter('#remove-trick')->click();

        $crawler = $client->request('GET', '/');
        $client->waitFor('h1');

        $nbTrick = $crawler->filter('.main-trick')->count();

        static::assertSame($oldNbTrick-1, $nbTrick);

        $client->quit();
    }
}
