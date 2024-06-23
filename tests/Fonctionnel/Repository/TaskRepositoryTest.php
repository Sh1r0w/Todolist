<?php

namespace App\tests\Fonctionnel\Repository;

use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\DependsExternal;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use App\Entity\Task;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;

class TaskRepositoryTest extends KernelTestCase
{

    /**
     * @var AbstractDatabaseTool
     */

    protected $databaseTool;

    protected $em;

    /**
     * The setUp function initializes the database tool and entity manager for testing purposes.
     */

    public function setUp(): void
    {
        parent::setUp();

        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();

        $this->em = static::getContainer()->get(EntityManagerInterface::class);
    }


    /**
     * This PHP function tests the TaskRepository by loading fixtures and checking the count of tasks.
     */

    #[DependsExternal('App\tests\Fonctionnel\Repository\UserRepositoryTest', 'testUserRepo')]
    public function testTaskRepo(): void
    {

        $this->databaseTool->loadAliceFixture([
            __DIR__ . '/TaskRepositoryTestFixtures.yaml'
        ]);


        $tasks = $this->em->getRepository(Task::class)->count([]);
        $this->assertEquals(20, $tasks);

    }

    /**
     * The tearDown function in PHP is used to clean up resources and unset variables after a test has
     * been executed.
     */
    
    protected function tearDown(): void
    {

        parent::tearDown();
        unset($this->databaseTool);

    }
}
