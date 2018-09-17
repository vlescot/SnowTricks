<?php
declare(strict_types=1);

namespace App\Tests;

use Symfony\Component\Panther\Client;
use Symfony\Component\Panther\DomCrawler\Crawler;

/**
 * Trait ModalConnectionTrait
 * @package App\Tests\FunctionalTest
 *
 * Create an user connection via modal windows
 */
trait ModalConnectionTrait
{
    public function getUserConnection(string $username, string $password, Client $client): Crawler
    {
        $crawler = $client->getCrawler();

        $crawler->filter('nav .nav-item:nth-child(2) span')->click();
        $client->waitFor('.modal');

        $connectionForm = $crawler->selectButton('Se connecter')->form();
        $connectionForm['login[_login]'] = $username;
        $connectionForm['login[_password]'] = $password;

        return $client->submit($connectionForm);
    }
}
