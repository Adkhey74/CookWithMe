<?php

namespace App\Controller;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RecipeController extends AbstractController
{
    #[Route('/api/recipes/search', name: 'search_recipes', methods: ['POST'])]
    public function search(Request $request, RecipeRepository $recipeRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $keyword = $data['keyword'] ?? null;

        if (!$keyword) {
            return new JsonResponse([
                'error' => 'Keyword is required for search.'
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        $recipes = $recipeRepository->findByKeyword($keyword);

        return new JsonResponse($recipes, JsonResponse::HTTP_OK);
    }

    #[Route('/api/recipes/by-category/{categoryId}', name: 'get_recipes_by_category', methods: ['GET'])]
    public function getRecipesByCategory(int $categoryId, RecipeRepository $recipeRepository): JsonResponse
    {
        $recipes = $recipeRepository->findBy(['category' => $categoryId]);

        if (!$recipes) {
            return new JsonResponse(['error' => 'No recipes found for the given category.'], JsonResponse::HTTP_NOT_FOUND);
        }

        return new JsonResponse($recipes, JsonResponse::HTTP_OK);
    }
}
