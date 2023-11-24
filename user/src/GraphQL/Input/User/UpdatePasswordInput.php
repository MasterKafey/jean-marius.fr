<?php

namespace App\GraphQL\Input\User;

use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Input]
class UpdatePasswordInput
{
    #[GQL\Field(type: 'String!')]
    private ?string $plainPassword;

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;
        return $this;
    }
}