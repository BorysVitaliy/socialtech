<?php

declare(strict_types=1);

namespace App\Service\Hasher\Contract;

interface HasherInterface
{
    public function hash(string $string): string;
}
