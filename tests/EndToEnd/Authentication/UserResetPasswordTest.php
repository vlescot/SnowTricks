<?php
declare(strict_types=1);

namespace App\Tests\EndToEnd\Authentication;

use App\Tests\EndToEnd\ModalUserHelperTrait;
use Symfony\Component\Panther\PantherTestCase;

final class UserResetPasswordTest extends PantherTestCase
{
    use ModalUserHelperTrait;


    public function testResetPasswordWithWrongToken()
    {
        $token = hash('sha512', uniqid('username', true));

        $client = static::createPantherClient('127.0.0.1', 8999);
        $crawler = $client->request('GET', '/change_password/'. $token);

        $client->waitFor('#flash-container strong');
        $flashMessage = $crawler->filter('#flash-container strong')->text();

        static::assertSame('Nous n\'avons pas pu vous identifier', $flashMessage);

        $client->quit();
    }


    public function testResetPasswordWithGoodToken()
    {
        $username = 'Mike';
        $password = 'FakePsw00';

        $client = static::createPantherClient('127.0.0.1', 8999);
        $client->request('GET', '/');
        $client->waitFor('h1');

        $oldUser = $this->getUser($username);
        $token = $oldUser->getToken();

        $crawler = $client->request('GET', '/change_password/'. $token);
        $client->waitFor('h3');

        $connectionForm = $crawler->selectButton('Réinitialiser')->form();
        $connectionForm['change_password[password][first]'] = $password;
        $connectionForm['change_password[password][second]'] = $password;
        $crawler = $client->submit($connectionForm);

        $client->waitFor('#flash-container strong');
        $flashMessage = $crawler->filter('#flash-container strong')->text();

        $newUser = $this->getUser($username);

        static::assertNotSame($oldUser->getPassword(), $newUser->getPassword());
        static::assertSame('Votre mot de passe à bien été modifié', $flashMessage);

        $client->quit();
    }
}
