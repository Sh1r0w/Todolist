<?php

namespace App\Tests\Unitaire\Entity;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase
{

    /**
     * The function `getEntity` creates a new User object with the provided email, username, password,
     * and roles.
     * 
     * @param string email The email parameter in the `getEntity` function is a string that represents
     * the email address of the user.
     * @param string username The username parameter in the `getEntity` function is a string that
     * represents the username of the user you are creating.
     * @param string password The `getEntity` function takes four parameters: ``, ``,
     * ``, and ``. The function creates a new `User` object, sets the email, username,
     * password, and roles for the user, and then returns the user object.
     * @param array role The `role` parameter in the `getEntity` function is an array that contains the
     * roles assigned to the user. These roles determine the permissions and access levels that the
     * user has within the system. For example, roles could be 'admin', 'editor', 'user', etc.
     * 
     * @return User An instance of the User class with the provided email, username, password, and
     * roles set.
     */

    public function getEntity(string $email, string $username, string $password, array $role): User
    {
        $user = new User();
        $user->setEmail($email);
        $user->setUsername($username);
        $user->setPassword($password);
        $user->setRoles($role);

        return $user;
    }

    /**
     * This PHP function counts the number of validation errors for a given User object.
     * 
     * @param User user The `countError` function takes two parameters:
     * @param int number The `` parameter in the `countError` function is an optional integer
     * parameter that specifies the expected count of errors that should be found when validating the
     * `User` object. If no value is provided for this parameter, the default value is set to 0. This
     * parameter allows you to specify
     */

    public function countError(User $user, int $number = 0): void
    {
        self::bootKernel();

        $container = static::getContainer();
        $error = $container->get('validator')->validate($user);

        $this->assertCount($number, $error);

    }

    /**
     * The function `testGood` creates a user entity with specific details and checks if there are any
     * errors associated with it.
     */

    public function testGood(): void
    {
        $user = $this->getEntity('test@email.com', 'superUser', '123456', ['ROLE_ADMIN']);
        self::countError($user, 0);
    }

    /**
     * The testError function checks for errors in creating entities with invalid data.
     */
    
    public function testError(): void
    {
        self::countError($this->getEntity('test@', 'superUser', '123456', ['ROLE_ADMIN']), 1);
        self::countError($this->getEntity('test@test.com', '', '123456', ['ROLE_ADMIN']), 1);
        self::countError($this->getEntity('test@test.com', 'Supername', '', ['ROLE_ADMIN']), 1);
        self::countError($this->getEntity('', '', '', ['ROLE_ADMIN']), 3);
    }

}
