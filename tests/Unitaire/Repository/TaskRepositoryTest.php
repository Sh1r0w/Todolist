<?php

namespace App\tests\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use App\Entity\Task;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;

class TaskRepositoryTest extends WebTestCase
{
    /**
     * @var AbstractDatabaseTool
     */

     protected $databaseTool;

     protected $em;

     public function setUp(): void
     {
        parent::setUp();

        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();

        $this->em = static::getContainer()->get(EntityManagerInterface::class);
     }

    public function testSomething(): void
    {
       //self::bootKernel();
       
        $this->databaseTool->loadAliceFixture([
            __DIR__ . '/TaskRepositoryTestFixtures.yaml'
        ]);

        $tasks = $this->em->getRepository(Task::class)->count([]);

        $this->assertEquals(20, $tasks);

    }

    protected function tearDown(): void
    {
        
        parent::tearDown();
        unset($this->databaseTool);

    }
}
