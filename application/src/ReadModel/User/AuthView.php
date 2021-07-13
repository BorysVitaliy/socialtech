<?php

declare(strict_types=1);

namespace App\ReadModel\User;

class AuthView
{
    public string $id;

    public string $nickName;

    public string $passwordHash;

    public string $role;
}
