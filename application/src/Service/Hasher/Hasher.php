<?php

declare(strict_types=1);

namespace App\Service\Hasher;

use App\Service\Hasher\Contract\HasherInterface;

class Hasher implements HasherInterface
{
    public function hash(string $string): string
    {
        return md5($string);
    }
}
