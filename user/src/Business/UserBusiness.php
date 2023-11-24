<?php

namespace App\Business;

use App\Entity\Token;
use App\Entity\TokenType;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserBusiness
{
    public function __construct(
        private readonly EntityManagerInterface      $entityManager,
        private readonly TokenBusiness               $tokenBusiness,
        private readonly UserPasswordHasherInterface $hasher
    )
    {
    }

    public function getCurrentUser(): ?User
    {
        return $this->tokenBusiness->getCurrentToken()?->getUser();
    }

    public function getUserByCredentials(string $email, string $plainPassword): ?User
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

        if (null === $user || !$this->hasher->isPasswordValid($user, $plainPassword)) {
            return null;
        }

        return $user;
    }
}