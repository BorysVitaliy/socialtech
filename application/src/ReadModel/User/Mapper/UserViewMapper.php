<?php

declare(strict_types=1);

namespace App\ReadModel\User\Mapper;

use App\Model\User\Entity\User;
use App\ReadModel\User\AuthView;
use App\ReadModel\User\Mapper\Contract\UserViewMapperInterface;
use App\ReadModel\User\ProfileView;

class UserViewMapper implements UserViewMapperInterface
{
    private string $dateFormat;

    public function __construct(string $dateFormat)
    {
        $this->dateFormat = $dateFormat;
    }

    public function toAuth(User $userEntity): AuthView
    {
        $user = new AuthView();
        $user->id = $userEntity->getId()->getValue();
        $user->nickName = $userEntity->getNickName();
        $user->passwordHash = $userEntity->getPasswordHash();

        return $user;
    }

    public function toProfile(User $userEntity): ProfileView
    {
        $user = new ProfileView();
        $user->id = $userEntity->getId()->getValue();
        $user->fullName = $userEntity->getName()->getFull();
        $user->nickName = $userEntity->getNickName();
        $user->createdAt = $userEntity->getCreatedAt()->format($this->dateFormat);

        return $user;
    }
}
