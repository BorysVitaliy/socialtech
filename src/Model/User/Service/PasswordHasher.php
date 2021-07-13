<?php

declare(strict_types=1);

namespace App\Model\User\Service;

use App\Model\User\Service\Contract\PasswordHasherInterface;
use RuntimeException;

class PasswordHasher implements PasswordHasherInterface
{
    private const ERR_MSG_GENERATE_HASH = 'Unable to generate hash.';

    public function hash(string $password): string
    {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        if ($hash === false) {
            throw new RuntimeException(self::ERR_MSG_GENERATE_HASH);
        }
        return $hash;
    }
}
