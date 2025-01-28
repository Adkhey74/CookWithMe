<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Entity\UserLike;
use App\Repository\RecipeRepository;
use App\Repository\UserLikeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

class UserLikeController extends AbstractController
{
  public function getRecipeWithLikeInfo(
    Recipe $data,
    UserLikeRepository $userLikeRepository,
    SerializerInterface $serializer
  ): JsonResponse {
    $user = $this->getUser();

    $isLikedByCurrentUser = false;
    if ($user) {
      $isLikedByCurrentUser = $userLikeRepository->findOneBy([
        'user' => $user,
        'recipe' => $data,
      ]) !== null;
    }

    $data->setIsLikedByCurrentUser($isLikedByCurrentUser);

    $responseData = $serializer->serialize($data, 'json', ['groups' => 'recipe:read']);

    return new JsonResponse($responseData, 200, [], true);
  }


  public function toggleLike(
    Request $request,
    RecipeRepository $recipeRepo,
    UserLikeRepository $userLikeRepo,
    EntityManagerInterface $entityManager
  ): JsonResponse {
    $user = $this->getUser();
    $data = json_decode($request->getContent(), true);
    $recipeId = $data['recipeId'] ?? null;

    if (!$recipeId) {
      return new JsonResponse(['message' => 'Recipe ID is required'], 400);
    }

    $recipe = $recipeRepo->find($recipeId);
    if (!$recipe) {
      return new JsonResponse(['message' => 'Recipe not found'], 404);
    }

    $existingLike = $userLikeRepo->findOneBy(['user' => $user, 'recipe' => $recipe]);

    if ($existingLike) {
      $recipe->removeUserLike($existingLike);
      $entityManager->remove($existingLike);
      $recipe->setNbLikes($recipe->getNbLikes() - 1);
      $entityManager->flush();

      return new JsonResponse(['message' => 'Like removed']);
    }

    $newLike = new UserLike();
    $newLike->setUser($user);
    $recipe->addUserLike($newLike);
    $recipe->setNbLikes($recipe->getNbLikes() + 1);

    $entityManager->persist($newLike);
    $entityManager->flush();

    return new JsonResponse(['message' => 'Like added']);
  }

  public function getLikedRecipes(UserLikeRepository $userLikeRepository, SerializerInterface $serializer): JsonResponse
  {
    $user = $this->getUser();

    if (!$user) {
      return new JsonResponse(['message' => 'Unauthorized'], 401);
    }

    $userLikes = $userLikeRepository->findBy(['user' => $user]);

    $recipes = array_map(fn($like) => $like->getRecipe(), $userLikes);

    $data = $serializer->serialize($recipes, 'json', ['groups' => 'recipe:read']);

    return new JsonResponse($data, 200, [], true);
  }
}
