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

    /**
     * The login function in PHP sends a POST request with the provided login credentials to a login
     * form.
     * 
     * @param login The `login` parameter in the `login` function represents the username or email that
     * the user is trying to log in with. It is typically used to identify the user's account during
     * the login process.
     * @param password The `login` function you provided seems to be a method for logging in a user by
     * submitting a form with the username and password fields. The `` parameter is used for the
     * username or email address, and the `` parameter is used for the user's password.
     */

    public function login($login, $password): void
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $this->client->submit($form, ['_username' => $login, '_password' => $password]);
    }

    /**
     * The testLogout function logs in as an admin, logs out, and asserts that the response status code
     * is 200.
     */

    public function testLogout(): void
    {
        $this->login('Admin', 'admin');
        $this->client->request('GET', '/logout');
        $this->client->followRedirect();
        $this->assertEquals('200', $this->client->getResponse()->getStatusCode());

    }
}
