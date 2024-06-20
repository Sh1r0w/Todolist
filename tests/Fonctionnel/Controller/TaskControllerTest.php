<?php

namespace App\Tests\Fonctionnel\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\Task;
use App\Entity\User;

Class TaskControllerTest extends WebTestCase
{
    private $client;

    private $em;

    public function setUp(): void
    {
        $this->client = static::createClient();
        
        $this->em = static::getContainer()->get(EntityManagerInterface::class);

    }

    public function login($user, $password): void
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $this->client->submit($form, ['_username' => $user, '_password' => $password]);
    }

    public function testListTask(): void
    {   
        $this->login('Admin', 'admin');
        $this->client->request('GET', '/task');
        $this->assertEquals('200', $this->client->getResponse()->getStatusCode());
       
    }

    public function testCreateAction(): void
    {
        $this->login('Admin', 'admin');
        $crawler = $this->client->request('GET', '/tasks/create');
        $form = $crawler->selectButton('Ajouter')->form();
        $this->client->submit($form, ['task[title]' => 'Test Titre', 'task[content]' => 'Test Content']);
        $this->client->followRedirect();
        $this->assertSelectorTextContains('.alert-success', 'Superbe ! La tâche a été bien ajoutée.');
    }

    public function testEditAction(): void
    {
        $this->login('Admin', 'admin');
        $crawler = $this->client->request('GET', 'tasks/1/edit');
        $form = $crawler->selectButton('Modifier')->form();
        $this->client->submit($form, ['task[title]' => 'Test Modification Titre', 'task[content]' => 'Test Modification Content']);
        $this->client->followRedirect();
        $this->assertSelectorTextContains('.alert-success', 'Superbe ! La tâche a bien été modifiée.');
    }

    public function testToggleTaskAction(): void
    {
        $this->login('Admin', 'admin');
        $crawler = $this->client->request('GET', 'task');
        $crawler->selectButton('Marquer comme faite');
        $this->client->request('POST', 'tasks/1/toggle');
        $this->client->followRedirect();
        $this->assertSelectorTextContains('.alert-success', 'Superbe ! La tâche Test Modification Titre a bien été marquée comme faite.');
    }

    public function testDeleteTaskAction(): void
    {

        $this->login('Admin', 'admin');
        $crawler = $this->client->request('GET', 'task');
        $crawler->selectButton('Supprimer');
        $this->client->request('GET', 'tasks/1/delete');
        $this->client->followRedirect();
        $this->assertSelectorTextContains('.alert-success', 'Superbe ! La tâche a bien été supprimée.');

    }

}