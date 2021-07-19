<?php

declare(strict_types=1);

namespace App\Model\User\Service\Exception;

use RuntimeException;
use Throwable;

class PasswordHashException extends RuntimeException
{
    private const ERR_MSG_GENERATE_HASH = 'Unable to generate hash.';

    public function __construct($message = self::ERR_MSG_GENERATE_HASH, $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
