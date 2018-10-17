<?php
declare(strict_types=1);

namespace App\Tests\EndToEnd;

use App\Domain\Entity\User;
use Symfony\Component\Panther\Client;
use Symfony\Component\Panther\DomCrawler\Crawler;

/**
 * Trait ModalConnectionTrait
 * @package App\Tests\FunctionalTest
 *
 * Create an user connection via modal windows
 */
trait ModalUserHelperTrait
{
    public function getUserConnection(string $username, string $password, Client $client): Crawler
    {
        $crawler = $client->getCrawler();

        $crawler->filter('nav .nav-item:nth-child(1) span')->click();
        $client->waitFor('.modal');

        $connectionForm = $crawler->selectButton('Se connecter')->form();
        $connectionForm['login[_login]'] = $username;
        $connectionForm['login[_password]'] = $password;

        return $client->submit($connectionForm);
    }

    /**
     * The Kernel is booted with env=dev, because Panther instantiate necessary the Kernel with dev environment...
     * A parallel instance of the Kernel have to be set and shot to avoid PantherClient bother
     *
     * @param string $username
     * @return mixed
     */
    private function getUser(string $username)
    {
        $kernel = parent::bootKernel(['environment' => 'dev']);

        $em = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        $userRepository = $em->getRepository(User::class);
        $user = $userRepository->findOneBy(['username' => $username]);

        $kernel->shutdown();

        return $user;
    }
}
