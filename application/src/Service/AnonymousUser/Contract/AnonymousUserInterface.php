<?php

declare(strict_types=1);

namespace App\Service\AnonymousUser\Contract;

interface AnonymousUserInterface
{
    public function create(string $ip, string $userAgent): string;

    public function ensureExistingUuid(string $uuid): void;
}
