<?php

namespace App\Tests\Fonctionnel\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{

    private $client;

    /**
     * The setUp function is used in PHP unit testing to initialize the client for testing purposes.
     */

    public function setUp(): void
    {
        $this->client = static::createClient();
    }

    /**
     * The login function in PHP sends a POST request with login credentials to a login form.
     * 
     * @param login The `login` function you provided seems to be a part of a PHP class or script that
     * handles a login process. It appears to be using Symfony's DomCrawler component to interact with
     * web pages.
     * @param password The `login` function you provided seems to be a part of a PHP class or script
     * that handles user login functionality. It appears to be using Symfony's web crawler component to
     * simulate a form submission for logging in.
     */

    public function login($login, $password): void
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $this->client->submit($form, ['_username' => $login, '_password' => $password]);
    }

    /**
     * The function `testListAction` tests the access control for listing users based on different user
     * roles.
     */

    public function testListAction(): void
    {
        $this->login('Anonyme', 'anon');
        $this->client->request('GET', '/user');
        $this->assertEquals('403', $this->client->getResponse()->getStatusCode());


        $this->login('Admin', 'admin');
        $this->client->request('GET', '/user');
        $this->assertEquals('200', $this->client->getResponse()->getStatusCode());
    }

    /**
     * The testUserCreate function tests the user creation process by logging in with different roles,
     * submitting a form to create a new user, and asserting the success message.
     */

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

    /**
     * The testEditAction function tests the editing functionality for a user account in a PHP
     * application.
     */
    
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