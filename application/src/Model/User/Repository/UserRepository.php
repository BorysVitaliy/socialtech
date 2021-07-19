<?php

declare(strict_types=1);

namespace App\Model\User\Repository;

use App\Model\User\Entity\User;
use App\Model\User\Repository\Contract\UserRepositoryInterface;
use App\Model\User\Repository\Hydrator\Contract\UserHydratorInterface;
use App\Model\User\Storage\Contract\UserStorageInterface;

class UserRepository implements UserRepositoryInterface
{
    private UserHydratorInterface $hydrator;

    private UserStorageInterface $storage;

    public function __construct(
        UserStorageInterface $storage,
        UserHydratorInterface $hydrator
    ) {
        $this->hydrator = $hydrator;
        $this->storage = $storage;
    }

    public function findByNickName(string $nickName): ?User
    {
        if (!$this->storage->exist($nickName)) {
            return null;
        }

        return $this->hydrator->hydrate(
            $this->storage->get($nickName)
        );
    }

    public function existNickName(string $nickName): bool
    {
        return $this->storage->exist($nickName);
    }

    public function save(User $user): void
    {
        $userData = $this->hydrator->extract($user);
        $this->storage->store($user->getNickName(), $userData);
    }
}
