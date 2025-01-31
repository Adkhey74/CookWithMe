<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Ingredient;
use App\Entity\Recipe;
use App\Entity\RecipeIngredient;
use App\Entity\Step;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class RecipeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        // Liste d'ingrédients cohérents en français
        $ingredients = [
            'Tomate',
            'Oignon',
            'Ail',
            'Poulet',
            'Bœuf',
            'Lait',
            'Beurre',
            'Œuf',
            'Farine',
            'Sucre',
            'Sel',
            'Poivre',
            'Huile d\'olive',
            'Carotte',
            'Pomme de terre',
            'Fromage',
            'Basilic',
            'Persil',
            'Riz',
            'Pâtes'
        ];

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
            $author->setRoles(['ROLE_USER']);
            $author->setPassword('password' . $i);
            $manager->persist($author);
            $authors[] = $author;
        }

        // Création de 200 recettes fictives
        for ($i = 1; $i <= 200; $i++) {
            $recipe = new Recipe();
            $recipe->setName('Recipe ' . $i);
            $recipe->setNbLikes(rand(0, 100));
            $recipe->setCreatedAt(new \DateTimeImmutable('now'));
            $recipe->setUpdatedAt(rand(0, 1) ? new \DateTimeImmutable('now') : null);
            $recipe->setCategory($categories[array_rand($categories)]);
            $recipe->setAuthor($authors[array_rand($authors)]);
            $manager->persist($recipe);

            // Ajout d'ingrédients à la recette
            for ($j = 1; $j <= rand(3, 10); $j++) {
                $ingredientName = $ingredients[array_rand($ingredients)];
                $ingredient = new Ingredient();
                $ingredient->setName($ingredientName);
                $manager->persist($ingredient);

                $Recipeingredient = new RecipeIngredient();
                $Recipeingredient->setIngredient($ingredient);
                $Recipeingredient->setQuantity(rand(1, 10));
                $Recipeingredient->setUnit($faker->randomElement(['g', 'kg', 'ml', 'l', 'cup', 'tbsp', 'tsp']));
                $Recipeingredient->setRecipe($recipe);
                $manager->persist($Recipeingredient);
            }

            // Ajout d'étapes à la recette
            for ($k = 1; $k <= rand(3, 10); $k++) {
                $step = new Step();
                $step->setNumber($k);
                $step->setTitle($faker->sentence);
                $step->setContent($faker->word);
                $step->setRecipeId($recipe);
                $manager->persist($step);
            }
        }

        // Sauvegarde de toutes les entités en base de données
        $manager->flush();
    }
}
