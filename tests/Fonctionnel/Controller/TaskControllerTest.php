<?php

namespace App\Tests\Fonctionnel\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\Task;
use App\Entity\User;

class TaskControllerTest extends WebTestCase
{

    private $client;

    private $em;

    /**
     * The setUp function initializes the client and entity manager for testing purposes in a PHP
     * environment.
     * 
     * @return void
     */
    public function setUp(): void
    {
        $this->client = static::createClient();

        $this->em = static::getContainer()->get(EntityManagerInterface::class);

    }

    /**
     * The login function in PHP sends a POST request with user credentials to a login form.
     * 
     * @param user The `` parameter in the `login` function likely represents the username or
     * email address of the user trying to log in. It is used to populate the `_username` field in the
     * login form when submitting the form for authentication.
     * 
     * @param password The `login` function you provided seems to be a part of a PHP application that
     * handles user authentication. The function is responsible for logging in a user by submitting a
     * form with the user's credentials (username and password).
     * 
     * @return void
     */
    public function login($user, $password): void
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $this->client->submit($form, ['_username' => $user, '_password' => $password]);
    }

    /**
     * The testListTask function logs in as an admin, sends a GET request to '/task', and asserts that
     * the response status code is 200.
     * 
     * @return void
     */
    public function testListTask(): void
    {
        $this->login('Admin', 'admin');
        $this->client->request('GET', '/task');
        $this->assertEquals('200', $this->client->getResponse()->getStatusCode());

    }

    /**
     * The testCreateAction function logs in as an admin, creates a new task with a title and content,
     * and asserts that the task was successfully added.
     * 
     * @return void
     */
    public function testCreateAction(): void
    {
        $this->login('Admin', 'admin');
        $crawler = $this->client->request('GET', '/tasks/create');
        $form = $crawler->selectButton('Ajouter')->form();
        $this->client->submit($form, ['task[title]' => 'Test Titre', 'task[content]' => 'Test Content']);
        $this->client->followRedirect();
        $this->assertSelectorTextContains('.alert-success', 'Superbe ! La tâche a été bien ajoutée.');
    }

    /**
     * This function tests the editing functionality of a task by logging in as an admin, submitting a
     * form with modified task title and content, and asserting the success message displayed after the
     * task is edited.
     * 
     * @return void
     */
    public function testEditAction(): void
    {
        $this->login('Admin', 'admin');
        $crawler = $this->client->request('GET', 'tasks/1/edit');
        $form = $crawler->selectButton('Modifier')->form();
        $this->client->submit($form, ['task[title]' => 'Test Modification Titre', 'task[content]' => 'Test Modification Content']);
        $this->client->followRedirect();
        $this->assertSelectorTextContains('.alert-success', 'Superbe ! La tâche a bien été modifiée.');
    }

    /**
     * The function `testToggleTaskAction` tests toggling a task's completion status in a PHP
     * application.
     * 
     * @return void
     */
    public function testToggleTaskAction(): void
    {
        $this->login('Admin', 'admin');
        $crawler = $this->client->request('GET', 'task');
        $crawler->selectButton('Marquer comme faite');
        $this->client->request('POST', 'tasks/1/toggle');
        $this->client->followRedirect();
        $this->assertSelectorTextContains('.alert-success', 'Superbe ! La tâche Test Modification Titre a bien été marquée comme faite.');
    }

    /**
     * The testDeleteTaskAction function tests the deletion of a task by logging in as an admin,
     * selecting the delete button, sending a request to delete a specific task, and asserting the
     * success message after deletion.
     * 
     * @return void
     */
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