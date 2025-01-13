<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use App\Entity\User;

class UserController extends AbstractController
{
    #[Route('/api/user', name: 'get_user', methods: ['GET'])]
    public function userInfo(): JsonResponse
    {
        /**
         * @var User|null $user
         */
        $user = $this->getUser();

        if (!$user) {
            throw new AccessDeniedException('User not authenticated');
        }

        return $this->json([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
            'firstName' => $user->getFirstName(),
            'lastName' => $user->getLastName(),
        ]);
    }
}
