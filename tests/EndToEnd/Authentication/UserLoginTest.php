<?php
declare(strict_types=1);

namespace App\Tests\EndToEnd\Authentication;

use App\Tests\EndToEnd\ModalUserHelperTrait;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Panther\PantherTestCase;

final class UserLoginTest extends PantherTestCase
{
    use ModalUserHelperTrait;

    /**
     * This test simulate an user authentication through the modal windows
     * and checks if the page display appropriates information
     */
    public function testGoodConnexionWithModal()
    {
        $client = static::createPantherClient('127.0.0.1', 8999);
        $client->request('GET', '/');
        $client->waitFor('h1');

        $crawler = $this->getUserConnection('root', 'root', $client);
        $client->waitFor('#flash-container');

        $flashMessage = $crawler->filter('#flash-container strong')->text();
        // NavBar links changed
        $navBarContent = $crawler->filter('nav .nav-item:nth-child(2) span')->text();
        $navBarImageProfile = $crawler->filter('nav .nav-item:nth-child(3) img')->count();

        static::assertSame(Response::HTTP_OK, $client->getResponse()->getStatus());
        static::assertSame('Bonjour root', $flashMessage);
        static::assertSame('Déconnexion', $navBarContent);
        static::assertSame(1, $navBarImageProfile);

        $client->quit();
    }

    public function testWrongUser()
    {
        $client = static::createPantherClient('127.0.0.1', 8999);
        $client->request('GET', '/');
        $client->waitFor('h1');

        $crawler = $this->getUserConnection('WrongUser', 'FakePsw', $client);

        $client->waitFor('#flash-container');
        $flashMessage = $crawler->filter('#flash-container strong')->text();

        static::assertSame('Nous n\'avons pas trouvé de membre avec l\'identifiant WrongUser', $flashMessage);

        $client->quit();
    }

    public function testWrongPassword()
    {
        $client = static::createPantherClient('127.0.0.1', 8999);
        $client->request('GET', '/');
        $client->waitFor('h1');

        $crawler = $this->getUserConnection('root', 'WrongPsw', $client);

        $client->waitFor('#flash-container');
        $flashMessage = $crawler->filter('#flash-container strong')->text();

        static::assertSame('Votre mot de passe est invalide', $flashMessage);

        $client->quit();
    }
}
