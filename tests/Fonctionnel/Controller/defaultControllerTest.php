<?php

namespace App\Tests\Fonctionnel\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class DefaultControllerTest extends WebTestCase
{

    private $client;

    /**
     * The setUp function creates a client object for testing purposes in PHP.
     */

    public function setUp(): void
    {
        $this->client = static::createClient();
    }

    /**
     * The login function in PHP sends a POST request with login credentials to a login form.
     * 
     * @param login The `login` parameter in the `login` function represents the username or email that
     * the user is trying to log in with. It is typically used to identify the user's account during
     * the login process.
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
     * The testDefaultController function logs in as an anonymous user, sends a GET request to the root
     * URL, and asserts that the h1 element contains a specific text related to a Todo List
     * application.
     */

    public function testDefaultController(): void
    {
        $this->login('Anonyme', 'anon');
        $this->client->request('GET', '/');
        $this->assertSelectorTextContains('h1', 'Bienvenue sur Todo List, l\'application vous permettant de gérer l\'ensemble de vos tâches sans effort !');
    }
}
