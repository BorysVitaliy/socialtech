<?php

declare(strict_types=1);

namespace App\Model\User\Repository\Contract;

use App\Model\User\Entity\User;

interface UserRepositoryInterface
{
    public function findByNickName(string $nickName): ?User;

    public function existNickName(string $nickName): bool;

    public function save(User $user): void;
}
