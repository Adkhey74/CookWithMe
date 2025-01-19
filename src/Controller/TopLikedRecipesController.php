<?php

namespace App\Controller;

use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class TopLikedRecipesController extends AbstractController
{
    public function __invoke(RecipeRepository $recipeRepository): JsonResponse
    {
        $recipes = $recipeRepository->findTopLikedRecipes();

        $data = [];
        foreach ($recipes as $recipe) {
            $data[] = [
                'id' => $recipe->getId(),
                'name' => $recipe->getName(),
                'nbLikes' => $recipe->getNbLikes(),
                'createdAt' => $recipe->getCreatedAt()->format('Y-m-d H:i:s'),
            ];
        }

        return new JsonResponse($data, 200);
    }
}
