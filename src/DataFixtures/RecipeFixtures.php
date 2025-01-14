<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Recipe;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RecipeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Création de 5 catégories fictives
        $categories = [];
        for ($i = 1; $i <= 5; $i++) {
            $category = new Category();
            $category->setName('Category ' . $i);
            $manager->persist($category);
            $categories[] = $category;
        }

        // Création de 5 utilisateurs fictifs
        $authors = [];
        for ($i = 1; $i <= 5; $i++) {
            $author = new User();
            $author->setEmail('user' . $i . '@example.com');
            $author->setFirstName('FirstName' . $i);
            $author->setLastName('LastName' . $i);
            $author->setRoles(['ROLE_USER']); // Attribuer un rôle par défaut
            $author->setPassword('password' . $i); // Exemple de mot de passe

            // Persister l'utilisateur
            $manager->persist($author);
            $authors[] = $author;
        }

        // Création de 20 recettes fictives
        for ($i = 1; $i <= 20; $i++) {
            $recipe = new Recipe();
            $recipe->setName('Recipe ' . $i);
            $recipe->setNbLikes(rand(0, 100));  // Nombre de likes aléatoire
            $recipe->setCreatedAt(new \DateTimeImmutable('now'));  // Date actuelle
            $recipe->setUpdatedAt(rand(0, 1) ? new \DateTimeImmutable('now') : null);  // Date de mise à jour aléatoire

            // Attribution aléatoire d'une catégorie et d'un auteur
            $recipe->setCategory($categories[array_rand($categories)]);
            $recipe->setAuthor($authors[array_rand($authors)]);

            // Persister la recette
            $manager->persist($recipe);
        }

        // Sauvegarde de toutes les entités en base de données
        $manager->flush();
    }
}
