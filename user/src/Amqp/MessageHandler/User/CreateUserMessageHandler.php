<?php

namespace App\Amqp\MessageHandler\User;

use App\Amqp\Message\User\CreateUserMessage;
use App\Doctrine\Trait\EntityManipulator;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsMessageHandler]
class CreateUserMessageHandler
{
    use EntityManipulator;

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    )
    {

    }

    /**
     * @throws \ReflectionException
     */
    public function __invoke(CreateUserMessage $message): void
    {
        $user = new User();

        $this->update($user, $message);
    }

    public function getEntityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }
}