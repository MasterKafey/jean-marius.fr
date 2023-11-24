<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['email'], groups: ['creation'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column]
    private ?string $firstname = null;

    #[ORM\Column]
    private ?string $lastname = null;

    #[Assert\Email]
    #[ORM\Column(unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private ?string $password = null;

    /** Must not be mapped to database schema */
    private ?string $plainPassword = null;

    #[ORM\Column(type: Types::SIMPLE_ARRAY, enumType: UserRole::class)]
    private array $roles;

    /** @var PersistentCollection<Token> $tokens */
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Token::class, cascade: ['remove', 'persist'])]
    private PersistentCollection $tokens;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;
        return $this;
    }

    public function getRoles(): array
    {
        return array_map(function(UserRole|string $role) {
            return !is_string($role) ? $role->value : $role;
        }, $this->roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    /** @return PersistentCollection<Token> */
    public function getTokens(): PersistentCollection
    {
        return $this->tokens;
    }

    /** @param PersistentCollection<Token> $tokens */
    public function setTokens(PersistentCollection $tokens): self
    {
        $this->tokens = $tokens;
        return $this;
    }

    public function eraseCredentials(): void
    {
        $this->setPlainPassword(null);
    }

    public function getUserIdentifier(): string
    {
        return $this->getEmail();
    }
}