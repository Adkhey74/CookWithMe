<?php

namespace App\Controller;

use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class TopLikedRecipesController extends AbstractController
{
    public function __invoke(RecipeRepository $recipeRepository, SerializerInterface $serializer): JsonResponse
    {
        $recipes = $recipeRepository->findTopLikedRecipes();

        $responseData = $serializer->serialize($recipes, 'json', ['groups' => 'recipe:read']);

        return new JsonResponse($responseData, 200, [], true);
    }
}
