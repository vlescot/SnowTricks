<?php
declare(strict_types=1);

namespace App\Tests\EndToEnd;

use App\Domain\Entity\User;
use Symfony\Component\Panther\PantherTestCase;

final class UserRegistrationTest extends PantherTestCase
{
    public function testUserRegistration()
    {
        $username = 'username';
        $email = 'username@mail.com';
        $password = 'azerty00';

        $client = static::createPantherClient('127.0.0.1', 8999);
        $crawler = $client->request('GET', '/');

        $crawler->filter('nav .nav-item:nth-child(3) span')->click();
        $client->waitFor('.modal');

        $registrationForm = $crawler->selectButton('S\'inscrire')->form();
        $registrationForm['registration[username]'] = $username;
        $registrationForm['registration[email]'] = $email;
        $registrationForm['registration[password][first]'] = $password;
        $registrationForm['registration[password][second]'] = $password;

        $crawler = $client->submit($registrationForm);

        $flashMessage = $crawler->filter('#flash-container strong')->text();

        static::assertSame('Bienvenue parmis nous, ' . $username . '. Un e-mail vient de t\'être envoyé pour confirmer ton compte.', $flashMessage);
    }

    /**
     * @param string $username
     *
     * @dataProvider provideData
     */
    // TODO
//    public function testUserConfirmation(string $username)
//    {
//        $kernel = static::bootKernel(['environment' => 'dev']);
//        $em = $kernel->getContainer()
//            ->get('doctrine')
//            ->getManager();
//        $newUser = $em->getRepository(User::class);
//        $user = $newUser->findOneBy(['username' => $username]);
//        $r = new \ReflectionClass($newUser);
//
//        if (null === $user) {
//            dump('USER NULL');
//        }
//    }
}
