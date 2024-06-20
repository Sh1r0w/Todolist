<?php

namespace App\Tests\Unitaire\Entity;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase
{
    public function getEntity(string $email, string $username, string $password, Array $role): User
    {
        $user = new User();
        $user->setEmail($email);
        $user->setUsername($username);
        $user->setPassword($password);
        $user->setRoles($role);

        return $user;
    }
    public function countError(User $user, int $number = 0): void
    {
        self::bootKernel();

        $container = static::getContainer();
        $error = $container->get('validator')->validate($user);

        $this->assertCount($number, $error);

    }

    public function testGood(): void
    {
        $user = $this->getEntity('test@email.com', 'superUser', '123456', ['ROLE_ADMIN']);
        self::countError($user, 0);
    }

    public function testError(): void
    {
        self::countError($this->getEntity('test@', 'superUser', '123456', ['ROLE_ADMIN']), 1);
        self::countError($this->getEntity('test@test.com', '', '123456', ['ROLE_ADMIN']), 1);
        self::countError($this->getEntity('test@test.com', 'Supername', '', ['ROLE_ADMIN']), 1);
        self::countError($this->getEntity('', '', '', ['ROLE_ADMIN']), 3);
    }

}
