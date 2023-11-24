<?php

namespace App\Doctrine\EntityListener\User;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserPasswordHasherListener
{
    public function __construct(
        private readonly UserPasswordHasherInterface $hasher,
    ) {

    }

    public function prePersist(User $user): void
    {
        $this->hashPassword($user);
    }

    public function preUpdate(User $user): void
    {
        $this->hashPassword($user);
    }

    public function hashPassword(User $user): void
    {
        if (null === $user->getPlainPassword()) {
            return;
        }

        $hashedPassword = $this->hasher->hashPassword($user, $user->getPlainPassword());
        $user->setPassword($hashedPassword);
    }
}