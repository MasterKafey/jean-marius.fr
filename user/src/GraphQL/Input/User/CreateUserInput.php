<?php

namespace App\GraphQL\Input\User;

use Overblog\GraphQLBundle\Annotation as GQL;
#[GQL\Input]
class CreateUserInput
{
    #[GQL\Field(type: 'String!')]
    private ?string $email = null;

    #[GQL\Field(type: 'String!')]
    private ?string $firstname = null;

    #[GQL\Field(type: 'String!')]
    private ?string $lastname = null;

    #[GQL\Field(type: 'String!')]
    private ?string $plainPassword = null;

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;
        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;
        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

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