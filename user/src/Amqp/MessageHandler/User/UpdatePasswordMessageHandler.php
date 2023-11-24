<?php

namespace App\Amqp\MessageHandler\User;

use App\Amqp\Message\User\UpdatePasswordMessage;
use App\Business\UserBusiness;
use App\Doctrine\Exception\EntityNotFoundException;
use App\Doctrine\Trait\EntityManipulator;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use ReflectionException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsMessageHandler]
class UpdatePasswordMessageHandler
{
    use EntityManipulator;

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserBusiness $userBusiness,
        private readonly UserPasswordHasherInterface $hasher
    ) {

    }

    /**
     * @throws EntityNotFoundException
     * @throws ReflectionException
     */
    public function __invoke(UpdatePasswordMessage $message): void
    {
        $user = $this->findOneBy(User::class, ['id' => $message->getId()]);
        $user->setPassword($this->hasher->hashPassword($user, $message->getPlainPassword()));
        $this->save($user);
    }

    public function getEntityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }
}