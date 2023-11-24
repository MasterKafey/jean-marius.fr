<?php

namespace App\Doctrine\Subscriber;

use App\Amqp\Message\Entity\LogMessage;
use App\Doctrine\Enum\LogOperationType;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Event\PostRemoveEventArgs;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\Messenger\MessageBusInterface;

class LogSubscriber implements EventSubscriber
{
    public function __construct(
        private readonly MessageBusInterface $bus
    )
    {

    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::postPersist,
            Events::postUpdate,
            Events::postRemove,
        ];
    }

    public function postPersist(PostPersistEventArgs $args): void
    {
        $entity = $args->getObject();
        $payload = $this->toArray($entity);

        $this->registerLog(LogOperationType::CREATE, get_class($entity), $payload);
    }

    public function postUpdate(PostUpdateEventArgs $args): void
    {
        $entity = $args->getObject();
        $changeSet = $args->getObjectManager()->getUnitOfWork()->getEntityChangeSet($entity);
        $payload = [];
        foreach ($changeSet as $fieldName => [$oldValue, $newValue]) {
            $payload[$fieldName] = [
                'old' => $oldValue,
                'new' => $newValue,
            ];
        }

        $this->registerLog(LogOperationType::UPDATE, get_class($entity), $payload);
    }

    public function postRemove(PostRemoveEventArgs $args): void
    {
        $entity = $args->getObject();
        $payload = [
            'id' => $entity->getId(),
        ];

        $this->registerLog(LogOperationType::DELETE, get_class($entity), $payload);
    }

    private function toArray($entity): array
    {
        return get_object_vars($entity);
    }

    private function registerLog(
        LogOperationType $operationType,
        string $entityType,
        array $payload
    ): void {
        $this->bus->dispatch(
            (new LogMessage())
                ->setOperationType($operationType)
                ->setEntityType($entityType)
                ->setPayload($payload)
                ->setOccurredAt(new \DateTime())
        );
    }
}