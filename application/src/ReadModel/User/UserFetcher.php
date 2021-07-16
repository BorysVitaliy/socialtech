<?php

declare(strict_types=1);

namespace App\ReadModel\User;

use App\Model\User\Entity\User;
use App\Model\User\Repository\Contract\UserRepositoryInterface;
use App\ReadModel\Exception\NotFoundException;
use App\ReadModel\User\Mapper\Contract\UserViewMapperInterface;

class UserFetcher
{
    private UserRepositoryInterface $repository;

    private UserViewMapperInterface $viewMapper;

    public function __construct(
        UserRepositoryInterface $repository,
        UserViewMapperInterface $viewMapper
    ) {
        $this->repository = $repository;
        $this->viewMapper = $viewMapper;
    }

    public function findForAuthByUserName(string $userName): AuthView
    {
        return $this->viewMapper->toAuth(
            $this->ensureExistingUser($userName)
        );
    }

    public function findForProfileByUserName(string $userName): ProfileView
    {
        return $this->viewMapper->toProfile(
            $this->ensureExistingUser($userName)
        );
    }

    /**
     * @param string $userName
     * @throws NotFoundException
     * @return User
     */
    private function ensureExistingUser(string $userName): User
    {
        $userEntity = $this->repository->getByNickName($userName);

        if (!$userEntity) {
            throw new NotFoundException('User is not found');
        }

        return $userEntity;
    }
}
