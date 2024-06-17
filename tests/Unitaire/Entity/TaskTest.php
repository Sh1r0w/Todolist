<?php

namespace App\Tests\Unitaire\Entity;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Entity\Task;
use App\Entity\User;

class TaskTest extends KernelTestCase
{

    public function getEntity(string $title, string $content): Task
    {
        $task = new Task();
        $task->setTitle($title);
        $task->setContent($content);
        $task->setUser(new User());
        $task->setCreatedAt(new \DateTime());

        return $task;
        
    }

    public function countError(Task $task, int $number = 0)
    {
        self::bootKernel();

        $container = static::getContainer();
        $error = $container->get('validator')->validate($task);

        $this->assertCount($number, $error);

    }
    public function testGood(): void
    {
        $task = $this->getEntity('test', 'content');

        self::countError($task, 0);

    }

    public function testError(): void
    {
        self::countError($this->getEntity('', 'test'), 1);
        self::countError($this->getEntity('test', ''), 1);
        self::countError($this->getEntity('', ''), 2);
    }

    public function testCreateAt(): void
    {
        $user = new Task();
        $user->setCreatedAt(new \DateTime('2024-06-16'));

        $this->assertEquals($user->getCreatedAt(), new \DateTime('2024-06-16'));

    }

}
