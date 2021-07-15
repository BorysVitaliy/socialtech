<?php

declare(strict_types=1);

namespace App\ReadModel\User;

use App\Model\User\Entity\User;
use App\Model\User\Repository\Contract\UserRepositoryInterface;
use App\ReadModel\Exception\NotFoundException;

class UserFetcher
{
    private UserRepositoryInterface $repository;

    private string $dateFormat;

    public function __construct(
        UserRepositoryInterface $repository,
        string $dateFormat
    ) {
        $this->repository = $repository;
        $this->dateFormat = $dateFormat;
    }

    public function findForAuthByUserName(string $userName): AuthView
    {
        $userEntity = $this->ensureExistingUser($userName);
        $user = new AuthView();
        $user->id = $userEntity->getId()->getValue();
        $user->nickName = $userEntity->getNickName();
        $user->passwordHash = $userEntity->getPasswordHash();

        return $user;
    }

    public function findForProfileByUserName(string $userName): ProfileView
    {
        $userEntity = $this->ensureExistingUser($userName);
        $user = new ProfileView();
        $user->id = $userEntity->getId()->getValue();
        $user->fullName = $userEntity->getName()->getFull();
        $user->nickName = $userEntity->getNickName();
        $user->createdAt = $userEntity->getCreatedAt()->format($this->dateFormat);

        return $user;
    }

    private function ensureExistingUser(string $userName): User
    {
        $userEntity = $this->repository->getByNickName($userName);

        if (!$userEntity) {
            throw new NotFoundException('User is not found');
        }

        return $userEntity;
    }
}
