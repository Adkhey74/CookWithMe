<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public const CATEGORIES = [
        'Entrées',
        'Plats principaux',
        'Desserts',
        'Boissons',
        'Végétarien',
        'Poissons et fruits de mer',
        'Fast Food',
        'Salades',
        'Soupes',
        'Petit-déjeuner',
    ];
    public function load(ObjectManager $manager): void
    {
        foreach (self::CATEGORIES as $key => $categoryName) {
            $category = new Category();
            $category->setName($categoryName);

            // Sauvegarde la catégorie dans une référence pour d'autres fixtures (ex. Recipe)
            $this->addReference('category_' . $key, $category);

            $manager->persist($category);
        }

        $manager->flush();
    }
}
