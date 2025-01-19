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
        // // Liste des utilisateurs fictifs à créer
        // $users = [
        //     ['firstName' => 'John', 'lastName' => 'Doe', 'email' => 'admin@example.com', 'password' => 'password123', 'roles' => ['ROLE_ADMIN']],
        //     ['firstName' => 'Jane', 'lastName' => 'Smith', 'email' => 'user@example.com', 'password' => 'password123', 'roles' => ['ROLE_USER']],
        // ];

        // foreach ($users as $key => $userData) {
        //     $user = new User();

        //     // On définit les attributs de l'utilisateur
        //     $user->setFirstName($userData['firstName']);
        //     $user->setLastName($userData['lastName']);
        //     $user->setEmail($userData['email']);
        //     $user->setRoles($userData['roles']);

        //     // Hash du mot de passe
        //     $hashedPassword = $this->passwordHasher->hashPassword($user, $userData['password']);
        //     $user->setPassword($hashedPassword);

        //     // Ajout d'une référence pour potentiellement l'utiliser ailleurs
        //     $this->addReference('user_' . $key, $user);

        //     // Persister l'utilisateur dans la base de données
        //     $manager->persist($user);
        // }

        // // Sauvegarde des utilisateurs
        // $manager->flush();
    }
}
