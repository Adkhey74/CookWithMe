<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use App\Repository\RecipeIngredientRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RecipeIngredientController extends AbstractController
{
    #[Route('/recipe/ingredient', name: 'app_recipe_ingredient')]
    public function index(): Response
    {
        return $this->render('recipe_ingredient/index.html.twig', [
            'controller_name' => 'RecipeIngredientController',
        ]);
    }
    #[Route('/api/recipe_ingredients', name: 'delete_recipe_ingredients_by_recipe', methods: ['DELETE'])]
    public function deleteRecipeIngredientsByRecipe(Request $request, RecipeIngredientRepository $recipeIngredientRepository): JsonResponse
    {
        // Récupérer l'ID de la recette via le paramètre de requête
        $recipeId = $request->query->get('recipe');

        if (!$recipeId) {
            return new JsonResponse(['error' => 'Le paramètre "recipe" est manquant'], JsonResponse::HTTP_BAD_REQUEST);
        }

        // Récupérer tous les ingrédients associés à cette recette
        $recipeIngredients = $recipeIngredientRepository->findBy(['recipe' => $recipeId]);

        if (empty($recipeIngredients)) {
            return new JsonResponse(['message' => 'Aucun ingrédient trouvé pour cette recette.'], JsonResponse::HTTP_OK);
        }

        // Supprimer tous les ingrédients associés à cette recette
        foreach ($recipeIngredients as $recipeIngredient) {
            $recipeIngredientRepository->remove($recipeIngredient, true);
        }

        return new JsonResponse(['message' => 'Tous les ingrédients de la recette ont été supprimés.'], JsonResponse::HTTP_OK);
    }
}
