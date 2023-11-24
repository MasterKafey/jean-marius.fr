<?php

namespace App\GraphQL\Mutation;

use App\Business\GoogleBusiness;
use App\Model\Calendar;
use Overblog\GraphQLBundle\Definition\Resolver\MutationInterface;

class CalendarMutation implements MutationInterface
{

    public function __construct(
        private readonly GoogleBusiness $googleBusiness,
    )
    {
    }

    public function addCalendar(string $id): Calendar
    {
        return $this->googleBusiness->addCalendar(
            (new Calendar())->setId($id)
        );
    }
}