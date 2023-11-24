<?php

namespace App\Doctrine\Exception;

use Doctrine\ORM\EntityNotFoundException as Base;

class EntityNotFoundException extends Base
{
    public function __construct(string $className, array $criteria = [], int $code = 0, \Throwable $previous = null)
    {
        $message = "$className with given criteria : " . json_encode($criteria) . ' was not found';

        parent::__construct($message, $code, $previous);
    }
}