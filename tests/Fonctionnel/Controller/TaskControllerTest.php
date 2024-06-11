<?php

namespace App\Tests\Fonctionnel\Controller;

use PHPUnit\Framework\TestCase;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

Class TaskControllerTest extends WebTestCase
{
    private $client;

    public function setUp(): void
    {
        $this->client = static::createClient();
        

    }

    public function login(): void
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $this->client->submit($form, ['_username' => 'Admin', '_password' => 'admin']);
    }

    public function testListTask(): void
    {   
        $this->login();
        $this->client->request('GET', '/task');
        $this->assertEquals('200', $this->client->getResponse()->getStatusCode());
       
    }

    public function testCreateAction(): void
    {
        $this->login();
        $crawler = $this->client->request('GET', '/tasks/create');
        $form = $crawler->selectButton('Ajouter')->form();
        $this->client->submit($form, ['task[title]' => 'Test Titre', 'task[content]' => 'Test Content']);
        $this->client->followRedirect();
        $this->assertSelectorTextContains('.alert-success', 'Superbe ! La tâche a été bien ajoutée.');
    }

}