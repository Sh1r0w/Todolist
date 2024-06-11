<?php

namespace App\Tests\Repository;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use App\Entity\User;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Doctrine\ORM\EntityManagerInterface;

class UserRepositoryTest extends WebTestCase
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

        $this->databaseTool->loadAliceFixture([
            __DIR__ . '/UserRepositoryTestFixtures.yaml'
        ]);

        $users = $this->em->getRepository(User::class)->count([]);

        $this->assertEquals(10, $users);

    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->databaseTool);
    }
}
