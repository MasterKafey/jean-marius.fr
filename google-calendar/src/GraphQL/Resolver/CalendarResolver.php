<?php

namespace App\GraphQL\Resolver;

use App\Business\GoogleBusiness;
use Overblog\GraphQLBundle\Definition\Resolver\QueryInterface;

class CalendarResolver implements QueryInterface
{
    public function __construct(
        private readonly GoogleBusiness $googleBusiness,
    )
    {

    }

    public function getCalendars(): array
    {
        return $this->googleBusiness->getCalendars();
    }
}