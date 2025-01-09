<?php

namespace App\Security;

use App\Entity\ApiToken;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class ApiTokenAuthenticator extends AbstractAuthenticator
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function supports(Request $request): ?bool
    {
        $excludedPaths = ['/api/login', '/api/signup'];
        if (in_array($request->getPathInfo(), $excludedPaths)) {
            return false;
        }

        return $request->headers->has('X-API-TOKEN');
    }

    public function authenticate(Request $request): SelfValidatingPassport
    {
        $tokenValue = $request->headers->get('X-API-TOKEN');

        $token = $this->entityManager->getRepository(ApiToken::class)
            ->findOneBy(['token' => $tokenValue]);

        if (!$token || $token->isExpired()) {
            throw new AuthenticationException('Invalid or expired token');
        }

        return new SelfValidatingPassport(
            new UserBadge($token->getUser()->getEmail())
        );
    }

    public function onAuthenticationSuccess(Request $request, $token, string $firewallName): ?JsonResponse
    {
        return null; // Laisse la requête continuer
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): JsonResponse
    {
        return new JsonResponse(
            ['error' => 'Authentication failed'],
            JsonResponse::HTTP_UNAUTHORIZED
        );
    }
}
