<?php

declare(strict_types=1);

namespace App\Queue\Message\User;

namespace App\Queue\Message\User;

use DateTimeImmutable;

class TrackAction
{
    private string $userId;

    private string $sourceLabel;

    private DateTimeImmutable $dateTimeImmutable;

    public function __construct(
        string $userId,
        string $sourceLabel,
        DateTimeImmutable $dateTimeImmutable
    ) {
        $this->userId = $userId;
        $this->sourceLabel = $sourceLabel;
        $this->dateTimeImmutable = $dateTimeImmutable;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getSourceLabel(): string
    {
        return $this->sourceLabel;
    }

    public function getDateTimeImmutable(): DateTimeImmutable
    {
        return $this->dateTimeImmutable;
    }
}
