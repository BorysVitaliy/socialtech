<?php

declare(strict_types=1);

namespace App\Service\AnonymousUser;

interface AnonymousUserInterface
{
    public function create(string $ip, string $userAgent): string;

    public function getId(string $uuid): string;
}
