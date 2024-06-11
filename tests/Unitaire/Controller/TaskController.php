<?php


namespace App\Tests\Unitaire\Controller;

use Symfony\Component\Form\Forms;
use Symfony\Component\Form\Test\TypeTestCase;
use App\Form\TaskType;
use App\Entity\Task;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;


class TaskControllerTest extends TypeTestCase
{

    private $em;
    public function setUp(): void
    {
        $this->em = $this->createMock(EntityManagerInterface::class);
        $this->factory = Forms::createFormFactory();
    }

    public function CreateAction()
    {
        $this->em->expects($this->once())
            ->method('persist')
            ->with($this->isInstanceOf(User::class));
        $this->em->expects($this->any())
            ->method('flush');
            
        $user = new User();
        $user->setPassword('test')
                ->setEmail('test@test.com')
                ->setUsername('User')
                ->setRoles(['ROLE_ADMIN']);
                $this->em->persist($user);
                $this->em->flush();


        $taskData = ['title' => 'test Title', 'content' => 'test Contents'];
        
                $idUs = $this->em->getRepository(User::class)->findOneBy(['id' => 1]);


        $task = new Task();

        $expected = new Task();

        $form = $this->factory->create(TaskType::class, $task);
        $form->submit($taskData);

        

        $this->em->expects($this->once())
            ->method('persist')
            ->with($this->isInstanceOf(Task::class));
        $this->em->expects($this->once())
            ->method('flush');
            
            $task->setUser($user)
                ->setTitle('test 1')
                ->setCreatedAt(new \DateTime());

            $this->em->persist($task);
            $this->em->flush();

            $this->assertEquals($expected, $task);

        $this->assertTrue($form->isValid());
        
    }



}