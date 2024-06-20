<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
class AppFixtures extends Fixture
{
    private $userPasswordHasher;
    
    public function __construct( UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }
    public function load(ObjectManager $manager,): void
    {
        $userAdmin = new User();
        $userAdmin->setUsername('Admin')
                    ->setRoles(["ROLE_ADMIN"])
                    ->setEmail('admin@todo.com')
                    ->setPassword($this->userPasswordHasher->hashPassword($userAdmin, "admin"));

        $manager->persist($userAdmin);

        $user = new User();
        $user->setUsername('User')
                ->setRoles(["ROLE_USER"])
                ->setEmail('user@todo.com')
                ->setPassword($this->userPasswordHasher->hashPassword($userAdmin, "user"));
        $manager->persist($user);
        
        $manager->flush();
    }
}
