<?php

declare(strict_types=1);

namespace App\Model\User\Storage\Contract;

interface UserStorageInterface
{
    public function get(string $nickName): array;

    public function store(string $nickName, array $data): void;

    public function exist(string $nickName): bool;
}
