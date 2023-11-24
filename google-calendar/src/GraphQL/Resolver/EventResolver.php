<?php

namespace App\GraphQL\Resolver;

use App\Business\GoogleBusiness;
use Overblog\GraphQLBundle\Definition\Resolver\QueryInterface;

class EventResolver implements QueryInterface
{
    public function __construct(
        private readonly GoogleBusiness $googleBusiness
    )
    {

    }

    public function getEvents(\DateTime $startedAt, \DateTime $endingAt): array
    {
        return $this->googleBusiness->getEvents($startedAt, $endingAt);
    }
}