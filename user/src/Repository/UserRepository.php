<?php

namespace App\Repository;

use App\Entity\TokenType;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public function findByToken(string $value, TokenType $type): ?User
    {
        $queryBuilder = $this->createQueryBuilder('user');
        $queryBuilder->join('user.tokens', 'token');
        $queryBuilder
            ->where(
                $queryBuilder->expr()->andX(
                    $queryBuilder->expr()->eq('token.type', ':type'),
                    $queryBuilder->expr()->eq('token.value', ':value')
                )
            )
        ;

        $queryBuilder->setParameter('type', $type->value);
        $queryBuilder->setParameter('value', $value);

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }
}