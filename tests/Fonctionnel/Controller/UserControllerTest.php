<?php

namespace App\Tests\Fonctionnel\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    private $client;

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

    public function testListAction(): void
    {
        $this->login('Anonyme', 'anon');
        $this->client->request('GET', '/user');
        $this->assertEquals('403', $this->client->getResponse()->getStatusCode());


        $this->login('Admin', 'admin');
        $this->client->request('GET', '/user');
        $this->assertEquals('200', $this->client->getResponse()->getStatusCode());
    }

    public function testUserCreate(): void
    {
        $this->login('Anonyme', 'anon');
        $this->client->request('GET', '/users/create');
        $this->assertEquals('403', $this->client->getResponse()->getStatusCode());


        $this->login('Admin', 'admin');
        $crawler = $this->client->request('POST', '/users/create');
        $form = $crawler->selectButton('Ajouter')->form();
        $this->client->submit($form, [
            'user[username]' => 'testCreateUser',
            'user[password][first]' => '0000',
            'user[password][second]' => '0000',
            'user[email]' => 'test@second.com',
        ]);

        $this->client->followRedirect();
        $this->assertAnySelectorTextContains('.alert-success', "L'utilisateur a bien été ajouté.");

    }

    public function testEditAction(): void
    {
        $this->login('Anonyme', 'anon');
        $this->client->request('GET', '/users/1/edit');
        $this->assertEquals('403', $this->client->getResponse()->getStatusCode());

        $this->login('Admin', 'admin');
        $this->client->request('GET', '/users/1/edit');
        $this->assertEquals('200', $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->request('GET', '/user');
        $crawler->selectButton('Edit');
        $crawler = $this->client->request('GET', '/users/1/edit');
        $this->assertEquals('200', $this->client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Modifier')->form();
        $this->client->submit($form, [
            'user[username]' => 'testEditUser',
            'user[password][first]' => '0001',
            'user[password][second]' => '0001',
            'user[email]' => 'test2@second.com',
        ]);
        $this->client->followRedirect();
        $this->assertAnySelectorTextContains('.alert-success', "Superbe ! L'utilisateur a bien été modifié");
    }

}