<?php

namespace App\Doctrine\Enum;

enum LogOperationType: string
{
    case CREATE = 'CREATE';
    case UPDATE = 'UPDATE';
    case DELETE = 'DELETE';
}
