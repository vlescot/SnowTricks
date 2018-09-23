<?php
declare(strict_types=1);

namespace App\Tests\EndToEnd\Authentication;

use App\Tests\EndToEnd\ModalUserHelperTrait;
use Symfony\Component\Panther\PantherTestCase;

// TODO Enabled to set Tests
final class UserResetPasswordTest extends PantherTestCase
{
    use ModalUserHelperTrait;


/*    public function testResetPasswordWithGoodUsername()
    {
        $username = 'Vincent';

        $client = static::createPantherClient('127.0.0.1', 8999);
        $crawler = $client->request('GET', '/');
        $client->waitFor('h1');

        $crawler->filter('nav .nav-item:nth-child(2) span')->click();
        $client->waitFor('.modal');

        $onClickFunction = $crawler->filter('.modal .modal-link')->first()->getAttribute('OnClick');

        // TODO Enable to give more DOM click pinpoint
        // $link->click(); give unknown error: Element <a class="modal-link"></a> is not clickable at point (x, y).
        // Other element would receive the click: <div class="modal-body"></div>

        static::assertSame('displayAuthenticationModal(\'reset_password\')', $onClickFunction);

        $crawler = $client->request('GET', '/authentication/reset_password');

        $connectionForm = $crawler->selectButton('Réinitialiser')->form();
        $connectionForm['reset_password[username]'] = $username;

        $crawler = $client->submit($connectionForm);

        try {
            $client->waitFor('#flash-container strong');
        }catch (\Exception $e) {
            $n = time();
            $client->takeScreenshot($n);
        }
        try{
            $flashMessage = $crawler->filter('#flash-container strong')->text();
        }catch (\Exception $e){
            $n = time();
            $client->takeScreenshot($n);
        }

        static::assertSame('Un e-mail vient de vous être envoyer', $flashMessage);

        $client->close();
    }


    public function testResetPasswordWithUnKnownUsername()
    {
        $username = 'randomName';

        $client = static::createPantherClient('127.0.0.1', 8999);
        $crawler = $client->request('GET', '/');
        $client->waitFor('h1');

        $crawler->filter('nav .nav-item:nth-child(2) span')->click();
        $client->waitFor('.modal');

        $onClickFunction = $crawler->filter('.modal .modal-link')->first()->getAttribute('OnClick');

        // TODO Enable to give more DOM click pinpoint
        // $link->click(); give unknown error: Element <a class="modal-link"></a> is not clickable at point (x, y).
        // Other element would receive the click: <div class="modal-body"></div>

        static::assertSame('displayAuthenticationModal(\'reset_password\')', $onClickFunction);

        $crawler = $client->request('GET', '/authentication/reset_password');

        $connectionForm = $crawler->selectButton('Réinitialiser')->form();
        $connectionForm['reset_password[username]'] = $username;

        $crawler = $client->submit($connectionForm);

        try {
            $client->waitFor('#flash-container strong');
        }catch (\Exception $e) {
            $n = time();
            $client->takeScreenshot($n);
        }

        try {
            $flashMessage = $crawler->filter('#flash-container strong')->text();
        }catch (\Exception $e){
            $n = time();
            $client->takeScreenshot($n);
        }
        static::assertSame('Nous n\'avons pas reconnu votre identifiant', $flashMessage);

        $client->close();
    }*/


    public function testChangePasswordWithGoodToken()
    {
        $username = 'Vincent';
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

        $client->close();
    }


    public function testChangePasswordWithWrongToken()
    {
        $token = hash('sha512', uniqid('username', true));

        $client = static::createPantherClient('127.0.0.1', 8999);
        $crawler = $client->request('GET', '/change_password/4984');

        $client->waitFor('#flash-container strong');
        $flashMessage = $crawler->filter('#flash-container strong')->text();

        static::assertSame('Nous n\'avons pas pu vous identifier', $flashMessage);

        $client->close();
    }
}

