<?php

namespace App\Entity;

enum UserRole: string
{
    case ADMIN = 'ROLE_ADMIN';
    case USER = 'ROLE_USER';
    case STUDENT = 'ROLE_STUDENT';
}
