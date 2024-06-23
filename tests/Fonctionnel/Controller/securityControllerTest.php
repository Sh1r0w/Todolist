<?php

namespace App\Tests\Fonctionnel\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{

    private $client;

    /**
     * The setUp function creates a client object for testing purposes in PHP.
     */

    public function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function login($login, $password): void
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $this->client->submit($form, ['_username' => $login, '_password' => $password]);
    }


    public function testLogout(): void
    {
        $this->login('Admin', 'admin');
        $this->client->request('GET', '/logout');
        $this->client->followRedirect();
        $this->assertEquals('200', $this->client->getResponse()->getStatusCode());

    }
}
