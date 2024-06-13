<?php

namespace App\tests\Fonctionnel\Repository;

use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\DependsExternal;
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


      #[DependsExternal('App\tests\Fonctionnel\Repository\UserRepositoryTest', 'testUserRepo')]
    public function testTaskRepo(): void
    {
       
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
