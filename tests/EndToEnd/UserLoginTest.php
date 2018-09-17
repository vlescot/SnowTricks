<?php
declare(strict_types=1);

namespace App\Tests\EndToEnd;

use App\Tests\ModalConnectionTrait;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Panther\PantherTestCase;

final class UserLoginTest extends PantherTestCase
{
    use ModalConnectionTrait;

    /**
     * This test simulate an user authentication through the modal windows
     * and checks if the page display appropriates information
     */
    public function testConnexionWithModal()
    {
        $client = static::createPantherClient('127.0.0.1', 8999);
        $client->request('GET', '/spins');

        $crawler = $this->getUserConnection('root', 'root', $client);

        $client->waitFor('#flash-container');

        // Display the flash message after connection
        $flashMessage = $crawler->filter('#flash-container strong')->text();
        // NavBar links changed
        $navBarContent = $crawler->filter('nav .nav-item:nth-child(2) span')->text();
        $navBarImageProfile = $crawler->filter('nav .nav-item:nth-child(3) img')->count();

        static::assertSame(Response::HTTP_OK, $client->getResponse()->getStatus());
        static::assertSame('Bonjour root', $flashMessage);
        static::assertSame('Déconnexion', $navBarContent);
        static::assertSame(1, $navBarImageProfile);
    }
}
