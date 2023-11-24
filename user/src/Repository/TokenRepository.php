<?php

namespace App\Repository;

use App\Entity\Token;
use App\Entity\TokenType;
use Doctrine\ORM\EntityRepository;

class TokenRepository extends EntityRepository
{
    public function getValidToken(string $value, ?TokenType $type = null, \DateTime $maxExpirationDateTime = null): ?Token
    {
        $queryBuilder = $this->createQueryBuilder('token');

        $andExpressions = [
            $queryBuilder->expr()->eq('token.value', ':value'),
            $queryBuilder->expr()->gt('token.expiresAt', ':maxExpirationDateTime')
        ];

        if (null !== $type) {
            $andExpressions[] = $queryBuilder->expr()->eq('token.type', ':type');
            $queryBuilder->setParameter('type', $type->value);
        }

        if (null === $maxExpirationDateTime) {
            $maxExpirationDateTime = new \DateTime();
        }

        $queryBuilder
            ->where(
                $queryBuilder->expr()->andX(
                    ...$andExpressions
                )
            )
        ;

        $queryBuilder
            ->setParameter('value', $value)
            ->setParameter('maxExpirationDateTime', $maxExpirationDateTime)
        ;

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }

    public function updateExpirationDateTime(string $value, TokenType $type, \DateTime $expirationDateTime): bool
    {
        $queryBuilder = $this->createQueryBuilder('token');

        $queryBuilder->update(
            $queryBuilder->set('token.expiresAt', ':expirationDateTime')
        );

        $queryBuilder
            ->where(
                $queryBuilder->expr()->eq('token.type', ':type'),
                $queryBuilder->expr()->eq('token.value', ':value')
            )
        ;

        $queryBuilder
            ->setParameter('type', $type->value)
            ->setParameter('value', $value)
            ->setParameter('expirationDateTime', $expirationDateTime)
        ;

        return $queryBuilder->getQuery()->execute();
    }
}