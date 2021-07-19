<?php

declare(strict_types=1);

namespace App\Model\User\Service;

use App\Model\User\Service\Contract\PasswordHasherInterface;
use App\Model\User\Service\Exception\PasswordHashException;

class PasswordHasher implements PasswordHasherInterface
{
    /**
     * @param string $password
     * @throws PasswordHashException
     * @return string
     */
    public function hash(string $password): string
    {
        $hash = password_hash($password, PASSWORD_BCRYPT);

        if ($hash === false) {
            throw new PasswordHashException();
        }

        return $hash;
    }
}
