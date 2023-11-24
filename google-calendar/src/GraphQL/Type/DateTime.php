<?php

namespace App\GraphQL\Type;

use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Scalar(name: 'DateTime')]
class DateTime
{
    public static function serialize(\DateTime $value)
    {
        return $value->format('Y-m-d H:i:s');
    }

    public static function parseValue($value)
    {
        return new \DateTime($value);
    }

    public static function parseLiteral($valueNode)
    {
        return new \DateTime($valueNode->value);
    }
}