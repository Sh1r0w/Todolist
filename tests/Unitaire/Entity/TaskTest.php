<?php

namespace App\Tests\Unitaire\Entity;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Entity\Task;
use App\Entity\User;

class TaskTest extends KernelTestCase
{

    /**
     * The function `getEntity` creates a new Task object with the provided title, content, user, and
     * creation timestamp.
     * 
     * @param string title The title parameter is a string that represents the title of the task
     * entity. It is used to set the title property of the Task object that is being created in the
     * getEntity function.
     * @param string content The `getEntity` function you provided creates a new `Task` entity with the
     * given title and content. It also sets a new `User` and the current date and time as the creation
     * date for the task.
     * 
     * @return Task An instance of the Task class with the provided title, content, a new User object,
     * and the current date and time set as the creation date.
     */

    public function getEntity(string $title, string $content): Task
    {
        $task = new Task();
        $task->setTitle($title);
        $task->setContent($content);
        $task->setUser(new User());
        $task->setCreatedAt(new \DateTime());

        return $task;

    }

    /**
     * The function `countError` validates a Task object and asserts that the number of errors found
     * matches the specified number.
     * 
     * @param Task task The `countError` function takes two parameters:
     * @param int number The `` parameter in the `countError` function is an optional integer
     * parameter that specifies the expected count of errors that should be found during the validation
     * of a `Task` object. If this parameter is not provided, the default value is set to 0.
     */

    public function countError(Task $task, int $number = 0)
    {
        self::bootKernel();

        $container = static::getContainer();
        $error = $container->get('validator')->validate($task);

        $this->assertCount($number, $error);

    }

    /**
     * The function "testGood" retrieves an entity and checks that it has no errors.
     */

    public function testGood(): void
    {
        $task = $this->getEntity('test', 'content');

        self::countError($task, 0);

    }

    /**
     * The testError function checks for errors when creating entities with empty values.
     */

    public function testError(): void
    {
        self::countError($this->getEntity('', 'test'), 1);
        self::countError($this->getEntity('test', ''), 1);
        self::countError($this->getEntity('', ''), 2);
    }

    /**
     * The testCreateAt function tests setting and getting the createdAt property of a Task object in
     * PHP.
     */
    
    public function testCreateAt(): void
    {
        $user = new Task();
        $user->setCreatedAt(new \DateTime('2024-06-16'));

        $this->assertEquals($user->getCreatedAt(), new \DateTime('2024-06-16'));

    }

}
