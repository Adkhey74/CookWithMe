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
        // Récupérer les paramètres du corps de la requête
        $data = json_decode($request->getContent(), true);

        $keyword = $data['keyword'] ?? null;

        if (!$keyword) {
            return new JsonResponse([
                'error' => 'Keyword is required for search.'
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        // Recherche des recettes par le mot-clé
        $recipes = $recipeRepository->findByKeyword($keyword);

        return new JsonResponse($recipes, JsonResponse::HTTP_OK);
    }
}
