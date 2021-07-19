<?php

declare(strict_types=1);

namespace App\Model\User\Storage\Contract;

interface TrackerStorageInterface
{
    public function store(array $content): void;
}
