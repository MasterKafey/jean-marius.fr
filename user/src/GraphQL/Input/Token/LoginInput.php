<?php

namespace App\GraphQL\Input\Token;

use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Input]
class LoginInput
{
    #[GQL\Field(type: 'String!')]
    private ?string $email = null;

    #[GQL\Field(type: 'String!')]
    private ?string $password = null;

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;
        return $this;
    }
}