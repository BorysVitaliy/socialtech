<?php

declare(strict_types=1);

namespace App\Security;

use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface
{
    private string $id;

    private string $nickName;

    private string $password;

    public function __construct(
        string $id,
        string $nickName,
        string $password
    ) {
        $this->id = $id;
        $this->nickName = $nickName;
        $this->password = $password;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getUserIdentifier(): string
    {
        return $this->nickName;
    }

    public function getUsername(): string
    {
        return $this->nickName;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getRoles(): array
    {
        return [];
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials(): void
    {
    }
}
