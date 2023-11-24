<?php

namespace App\GraphQL\Mutation;

use App\Business\TokenBusiness;
use App\Business\UserBusiness;
use App\Entity\Token;
use App\Entity\TokenType;
use Doctrine\ORM\EntityManagerInterface;
use Overblog\GraphQLBundle\Definition\Resolver\MutationInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

class TokenMutation implements MutationInterface
{
    public function __construct(
        private readonly TokenBusiness $tokenBusiness,
        private readonly UserBusiness  $userBusiness,
        private readonly EntityManagerInterface $entityManager
    )
    {
    }

    public function login(array $input): Token
    {
        $user = $this->userBusiness->getUserByCredentials($input['email'], $input['password']);

        if (null === $user) {
            throw new CustomUserMessageAuthenticationException('Bad credentials.');
        }

        $token = $this->tokenBusiness->createToken($user, TokenType::AUTHENTICATION);

        $this->entityManager->persist($token);
        $this->entityManager->flush();

        return $token;
    }
}