<?php

declare(strict_types=1);

namespace App\Model\User\Repository\Hydrator\Contract;

use App\Model\User\Entity\User;

interface UserHydratorInterface
{
    public function hydrate(array $user): User;

    public function extract(User $user): array;
}
