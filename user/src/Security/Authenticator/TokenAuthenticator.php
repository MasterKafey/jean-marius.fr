<?php

namespace App\Security\Authenticator;

use App\Business\TokenBusiness;
use App\Business\UserBusiness;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class TokenAuthenticator extends AbstractAuthenticator
{
    public function __construct(
        private readonly TokenBusiness $tokenBusiness,
        private readonly UserBusiness  $userBusiness
    )
    {

    }

    public function supports(Request $request): ?bool
    {
        return null !== $this->tokenBusiness->getCurrentTokenInRequest();
    }

    public function authenticate(Request $request): Passport
    {
        $user = $this->userBusiness->getCurrentUser();

        if (null === $user) {
            throw new CustomUserMessageAuthenticationException('Invalid authentication token');
        }

        return new SelfValidatingPassport(new UserBadge($user->getUserIdentifier(), function() use ($user) {
            return $user;
        }));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new JsonResponse([
            'result' => false,
            'errors' => [
                $exception->getMessage(),
            ]
        ]);
    }
}