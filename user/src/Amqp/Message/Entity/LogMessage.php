<?php

namespace App\Amqp\Message\Entity;

use App\Doctrine\Enum\LogOperationType;

class LogMessage
{
    private string $entityType;

    private LogOperationType $operationType;

    private array $payload = [];

    private \DateTime $occurredAt;

    public function getEntityType(): string
    {
        return $this->entityType;
    }

    public function setEntityType(string $entityType): self
    {
        $this->entityType = $entityType;
        return $this;
    }

    public function getOperationType(): LogOperationType
    {
        return $this->operationType;
    }

    public function setOperationType(LogOperationType $operationType): self
    {
        $this->operationType = $operationType;
        return $this;
    }

    public function getPayload(): array
    {
        return $this->payload;
    }

    public function setPayload(array $payload): self
    {
        $this->payload = $payload;
        return $this;
    }

    public function getOccurredAt(): \DateTime
    {
        return $this->occurredAt;
    }

    public function setOccurredAt(\DateTime $occurredAt): self
    {
        $this->occurredAt = $occurredAt;
        return $this;
    }
}