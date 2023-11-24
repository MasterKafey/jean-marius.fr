<?php

namespace App\Entity;

enum TokenType: string
{
    case FORGOT_PASSWORD = 'FORGOT_PASSWORD';
    case AUTHENTICATION = 'AUTHENTICATION';
    case REGISTRATION = 'REGISTRATION';
}
