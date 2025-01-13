<?php

namespace App\Controller;

use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AuthController
{
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $passwordHasher;
    private JWTTokenManagerInterface $jwtManager;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher,
        JWTTokenManagerInterface $jwtManager
    ) {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
        $this->jwtManager = $jwtManager;
    }

    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function login(): JsonResponse
    {
        return new JsonResponse(['message' => 'Login endpoint handled by json_login']);
    }

    #[Route('/api/signup', name: 'api_signup', methods: ['POST'])]
    public function signup(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $lastName = $data['lastName'];
        $firstName = $data['firstName'];
        $email = $data['email'];
        $password = $data['password'];

        if (!$email || !$password) {
            return new JsonResponse(['message' => 'Email et mot de passe sont requis.'], Response::HTTP_BAD_REQUEST);
        }

        $existingUser = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
        if ($existingUser) {
            return new JsonResponse(['message' => 'Un utilisateur avec cet email existe déjà.'], Response::HTTP_CONFLICT);
        }

        $user = new User();
        $user->setLastName($lastName);
        $user->setFirstName($firstName);
        $user->setEmail($email);
        $user->setPassword($this->passwordHasher->hashPassword($user, $password));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $token = $this->jwtManager->create($user);

        return new JsonResponse([
            'message' => 'Inscription réussie.',
            'token' => $token
        ], Response::HTTP_CREATED);
    }
}
