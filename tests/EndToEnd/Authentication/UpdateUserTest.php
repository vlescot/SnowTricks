<?php
declare(strict_types=1);

namespace App\Tests\EndToEnd\Authentication;

use App\Tests\EndToEnd\ModalUserHelperTrait;
use Symfony\Component\Panther\PantherTestCase;

final class UpdateUserTest extends PantherTestCase
{
    use ModalUserHelperTrait;


    public function testUserUpdate()
    {
        $oldUser = $this->getUser('Vincent');

        $client = static::createPantherClient('127.0.0.1', 8999);
        $client->request('GET', '/');

        $crawler = $this->getUserConnection('Vincent', 'azerty', $client);

        $client->waitFor('#flash-container strong');
        $crawler->filter('nav .nav-item:nth-child(3) span')->click();
        $client->waitFor('.modal');

        $connectionForm = $crawler->selectButton('Enregistrer')->form();
        $connectionForm['update_user[email]'] = 'newEmail@mail.com';
        $connectionForm['update_user[password][first]'] = 'newPassword';
        $connectionForm['update_user[password][second]'] = 'newPassword';
        $crawler = $client->submit($connectionForm);

        $client->waitFor('#flash-container strong');
        $flashMessage = $crawler->filter('#flash-container strong')->text();

        $newUser = $this->getUser('Vincent');

        static::assertNotSame($oldUser->getPassword(), $newUser->getPassword());
        static::assertNotSame($oldUser->getEmail(), $newUser->getEmail());
        static::assertSame('Ton profil a bien été mise à jour', $flashMessage);

        $client->quit();
    }
}
