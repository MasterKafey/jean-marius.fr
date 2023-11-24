<?php

namespace App\Amqp\MessageHandler\User;

use App\Amqp\Message\User\DeleteUserMessage;
use App\Doctrine\Exception\EntityNotFoundException;
use App\Doctrine\Trait\EntityManipulator;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class DeleteUserMessageHandler
{
    use EntityManipulator;

    public function __construct(
        private readonly EntityManagerInterface $entityManager
    )
    {

    }

    /**
     * @throws EntityNotFoundException
     */
    public function __invoke(DeleteUserMessage $message): void
    {
        $this->remove(User::class, $message->getId());
    }

    public function getEntityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }
}