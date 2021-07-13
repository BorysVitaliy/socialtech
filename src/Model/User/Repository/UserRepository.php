<?php

declare(strict_types=1);

namespace App\Model\User\Repository;

use App\Model\User\Entity\User;
use App\Model\User\Repository\Contract\UserRepositoryInterface;
use App\Model\User\Repository\Hydrator\UserHydratorInterface;

class UserRepository implements UserRepositoryInterface
{
    private const PATTERN_USER_FILE_NAME = '%s.json';

    private UserHydratorInterface $hydrator;

    public function __construct(UserHydratorInterface $hydrator)
    {
        $this->hydrator = $hydrator;
    }

    public function getByNickName(string $nickName): ?User
    {
        if (!$this->existNickName($nickName)) {
            return null;
        }

        $data = file_get_contents($this->getUserFileName($nickName));
        $data = json_decode($data, true, 512, JSON_THROW_ON_ERROR);

        return $this->hydrator->hydrate($data);
    }

    public function existNickName(string $nickName): bool
    {
        return file_exists($this->getUserFileName($nickName));
    }

    public function persist(User $user): void
    {
        $userData = $this->hydrator->extract($user);

        file_put_contents(
            $this->getUserFileName($user->getNickName()),
            json_encode($userData, JSON_THROW_ON_ERROR)
        );
    }

    private function getUserFileName(string $nickName): string
    {
        return sprintf(
            self::PATTERN_USER_FILE_NAME,
            $nickName
        );
    }
}
