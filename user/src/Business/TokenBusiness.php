<?php

namespace App\Business;

use App\Entity\Token;
use App\Entity\TokenType;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use http\Header;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class TokenBusiness
{
    private const TOKEN_HEADER = 'Authorization';
    private const STARTING_AUTHORIZATION_VALUE = 'Bearer ';

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly RequestStack           $requestStack,
    )
    {

    }

    public function generateTokenValue($length = 64): string
    {
        return bin2hex(random_bytes($length / 2));
    }

    public function createToken(User $user, TokenType $type): Token
    {
        $token = $this->entityManager->getRepository(Token::class)->findOneBy(['user' => $user, 'type' => $type]);

        if (null === $token) {
            $token = (new Token())
                ->setUser($user)
                ->setType($type);
        }

        $this->refreshToken($token);

        return $token;
    }

    public function refreshToken(Token $token): void
    {
        $token
            ->setValue($this->generateTokenValue())
            ->setExpiresAt((new \DateTime())->add($this->getExpirationDateInterval($token->getType())));
    }

    public function getCurrentToken(): ?Token
    {
        $value = $this->getCurrentTokenInRequest();
        return $this->getToken($value, TokenType::AUTHENTICATION);
    }

    public function getToken(string $value, TokenType $type): ?Token
    {
        return $this->entityManager->getRepository(Token::class)->getValidToken($value, $type);
    }

    public function getCurrentTokenInRequest(): ?string
    {
        $value = $this->requestStack->getMainRequest()->headers->get(self::TOKEN_HEADER);

        if (null === $value || !str_starts_with($value, self::STARTING_AUTHORIZATION_VALUE)) {
            return null;
        }

        return substr($value, strlen(self::STARTING_AUTHORIZATION_VALUE));
    }

    public function getExpirationDateInterval(TokenType $type): \DateInterval
    {
        return new \DateInterval('P1D');
    }
}