<?php

declare(strict_types=1);

namespace App\Model\User\Entity;

use DateTimeImmutable;

class User
{
    private Id $id;

    private string $nickName;

    private DateTimeImmutable $createdAt;

    private Name $name;

    private string $passwordHash;

    private function __construct(
        Id $id,
        string $nickName,
        DateTimeImmutable $createdAt,
        Name $name,
        string $passwordHash
    ) {
        $this->id = $id;
        $this->nickName = $nickName;
        $this->createdAt = $createdAt;
        $this->name = $name;
        $this->passwordHash = $passwordHash;
    }

    public static function create(
        Id $id,
        string $nickName,
        DateTimeImmutable $createdAt,
        Name $name,
        string $passwordHash
    ): self {
        return new self($id, $nickName, $createdAt, $name, $passwordHash);
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getNickName(): string
    {
        return $this->nickName;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getPasswordHash(): ?string
    {
        return $this->passwordHash;
    }

    public function getName(): Name
    {
        return $this->name;
    }
}
