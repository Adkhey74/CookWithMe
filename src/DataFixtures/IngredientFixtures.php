<?php

namespace App\DataFixtures;

use App\Entity\Ingredient;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class IngredientFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
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

        foreach ($ingredients as $name) {
            $ingredient = new Ingredient();
            $ingredient->setName($name); // Définit le nom de l’ingrédient

            $manager->persist($ingredient); // Persist l’entité
        }

        $manager->flush(); // Enregistre toutes les entités en base
    }
    public static function getGroups(): array
    {
        return ['ingredients'];
    }
}
