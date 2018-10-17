<?php
declare(strict_types=1);

namespace App\Tests\EndToEnd\Authentication;

use App\Domain\Entity\User;
use App\Tests\EndToEnd\ModalUserHelperTrait;
use Symfony\Component\Panther\PantherTestCase;

/**
 * User registration and user confirmation are on the same function the guarantee the order of the functions called
 * and to make sure that the database is persisted with the same user.
 *
 * On User Confirmation, the Kernel is booted with env=dev,
 * because Panther instantiate necessary the Kernel with dev environment...
 */
final class UserRegistrationTest extends PantherTestCase
{
    use ModalUserHelperTrait;


    public function testUserRegistrationAndConfirmation()
    {
        // User Registration
        $username = 'username';
        $email = 'username@mail.com';
        $password = 'azerty00';


        $client = static::createPantherClient('127.0.0.1', 8999);
        $crawler = $client->request('GET', '/');
        $client->waitFor('h1');

        $crawler->filter('nav .nav-item:nth-child(2) span')->click();
        $client->waitFor('.modal');

        $registrationForm = $crawler->selectButton('S\'inscrire')->form();
        $registrationForm['registration[username]'] = $username;
        $registrationForm['registration[email]'] = $email;
        $registrationForm['registration[password][first]'] = $password;
        $registrationForm['registration[password][second]'] = $password;

        $crawler = $client->submit($registrationForm);
        $client->waitFor('#flash-container strong');
        $flashMessage = $crawler->filter('#flash-container strong')->text();

        static::assertSame('Bienvenue parmis nous, ' . $username . '. Un e-mail vient de t\'être envoyé pour confirmer ton compte.', $flashMessage);


        // User Confirmation
        $user = $this->getUser($username);
        $rolesBefore = $user->getRoles();
        $token = $user->getToken();

        $crawler = $client->request('GET', '/confirmation/'. $token);
        $client->waitFor('#flash-container');
        $flashMessage = $crawler->filter('#flash-container strong')->text();

        $user = $this->getUser($username);
        $rolesAfter = $user->getRoles();

        static::assertSame([], $rolesBefore);
        static::assertContains('ROLE_USER', $rolesAfter);
        static::assertSame('Bonjour '. $username, $flashMessage);


        $client->quit();
    }

    public function testWrongConfirmation()
    {
        $token = hash('sha512', uniqid('username', true));

        $client = static::createPantherClient('127.0.0.1', 8999);
        $crawler = $client->request('GET', '/confirmation/'. $token);
        $client->waitFor('#flash-container');

        $flashMessage = $crawler->filter('#flash-container strong')->text();

        static::assertSame('Nous n\'avons pas pu vous authentifier.', $flashMessage);

        $client->quit();
    }
}
