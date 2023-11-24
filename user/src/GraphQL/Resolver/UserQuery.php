<?php

namespace App\GraphQL\Resolver;

use App\Business\UserBusiness;
use App\Entity\User;
use Overblog\GraphQLBundle\Definition\Resolver\QueryInterface;

class UserQuery implements QueryInterface
{
    public function __construct(
        private readonly UserBusiness $userBusiness,
    )
    {

    }

    public function getUser(): User
    {
        return $this->userBusiness->getCurrentUser();
    }
}