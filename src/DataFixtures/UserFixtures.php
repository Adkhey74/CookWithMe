<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $users = [
            ['name' => 'User1', 'email' => 'admin@example.com', 'password' => 'password123', 'roles' => ['ROLE_ADMIN']],
            ['name' => 'User2', 'email' => 'user@example.com', 'password' => 'password123', 'roles' => ['ROLE_USER']],
        ];

        foreach ($users as $key => $userData) {
            $user = new User();
            $user->setName($userData['name']);
            $user->setEmail($userData['email']);
            $user->setRoles($userData['roles']);
            $hashedPassword = $this->passwordHasher->hashPassword($user, $userData['password']);
            $user->setPassword($hashedPassword);

            $this->addReference('user_' . $key, $user);

            $manager->persist($user);
        }

        $manager->flush();
    }
}
