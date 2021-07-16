<?php

declare(strict_types=1);

namespace App\ReadModel\User\Mapper\Contract;

use App\Model\User\Entity\User;
use App\ReadModel\User\AuthView;
use App\ReadModel\User\ProfileView;

interface UserViewMapperInterface
{
    public function toAuth(User $user): AuthView;

    public function toProfile(User $user): ProfileView;
}
