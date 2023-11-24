<?php

namespace App\Amqp\MessageHandler\User;

use App\Amqp\Message\User\UpdateUserMessage;
use App\Doctrine\Exception\EntityNotFoundException;
use App\Doctrine\Trait\EntityManipulator;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use ReflectionException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class UpdateUserMessageHandler
{
    use EntityManipulator;

    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {

    }

    /**
     * @throws EntityNotFoundException
     * @throws ReflectionException
     */
    public function __invoke(UpdateUserMessage $message): void
    {
        $user = $this->findOneBy(User::class, ['id' => $message->getId()]);
        $this->update($user, $message);
    }

    public function getEntityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }
}