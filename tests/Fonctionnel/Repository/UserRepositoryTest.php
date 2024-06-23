<?php

namespace App\tests\Fonctionnel\Repository;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use App\Entity\User;
use App\Repository\UserRepository;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

class UserRepositoryTest extends KernelTestCase
{

    /**
     * @var AbstractDatabaseTool
     */

    protected $databaseTool;

    protected $em;

    private $user;

    private $repo;

    /**
     * The setUp function initializes database tools, entity manager, and user repository for testing
     * purposes.
     */

    public function setUp(): void
    {
        parent::setUp();


        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();

        $this->em = static::getContainer()->get(EntityManagerInterface::class);

        $this->repo = static::getContainer()->get(UserRepository::class);
    }

    /**
     * The function `testUserRepo` loads fixtures and checks if the number of users in the repository
     * matches the expected count.
     */

    public function testUserRepo(): void
    {

        $this->databaseTool->loadAliceFixture([
            __DIR__ . '/UserRepositoryTestFixtures.yaml'
        ]);

        $users = $this->em->getRepository(User::class)->count([]);

        $this->assertEquals(12, $users);

    }

    /**
     * The function `testUpdatePassword` tests the functionality of upgrading a user's password in a
     * PHP application.
     */

    public function testUpdatePassword(): void
    {
        $user = new User();
        $user->setPassword('old_password');
        $user->setEmail('test@gmail.com');
        $user->setUsername('reTest');
        $newHashedPassword = 'new_password';

        $this->em->persist($user);
        $this->em->flush();

        $this->repo->upgradePassword($user, $newHashedPassword);

        $this->assertSame($newHashedPassword, $user->getPassword());

    }

    /**
     * The function `testUpgradePasswordError` tests the upgradePassword method with an unsupported
     * user, expecting an UnsupportedUserException.
     */

    public function testUpgradePasswordError(): void
    {
        $unsupportedUser = $this->createMock(PasswordAuthenticatedUserInterface::class);
        $newHashedPassword = 'test';

        $this->expectException(UnsupportedUserException::class);
        $this->repo->upgradePassword($unsupportedUser, $newHashedPassword);

    }

    /**
     * The tearDown function in PHP is used to clean up resources and unset variables after a test case
     * has been executed.
     */
    
    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->databaseTool);
    }
}
