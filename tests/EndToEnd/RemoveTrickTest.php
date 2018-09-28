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
        $client->waitFor('h1');

        $oldNbTrick = $crawler->filter('.main-trick')->count();

        // Hide Symfony toolbar prevents errors
        $crawler->filter('a[title="Close Toolbar"]')->click();

        // Get the Gutterball trick div
        $trickName = '';
        $n = 0;
        while ($trickName !== 'Gutterball') {
            $trickName = $crawler->filter('.main-trick-title')->eq($n++)->text();
        }

        $crawler->filter('.main-trick')->eq($n - 1)->filter('.fa-trash-alt')->click();
        $client->waitFor('.modal');
        $crawler->filter('.modal #remove-trick')->click();
        $client->wait(3);

        $crawler = $client->request('GET', '/');
        $client->waitFor('h1');

        $nbTrick = $crawler->filter('.main-trick')->count();

        // TODO 5 is the number of times, that the thumbnails are showed on the view
        // in order to activate some front-end features as loading on scroll down
        static::assertSame($oldNbTrick - 1 * 5, $nbTrick);

        $client->quit();
    }
}
