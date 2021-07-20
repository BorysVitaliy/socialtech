<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\Action\Track;

use App\Queue\Message\User\TrackAction;
use DateTimeImmutable;
use Symfony\Component\Messenger\MessageBusInterface;

class Handler
{
    private MessageBusInterface $messageBus;

    public function __construct(
        MessageBusInterface $messageBus
    ) {
        $this->messageBus = $messageBus;
    }

    public function handle(Command $command): void
    {
        $this->messageBus->dispatch(
            new TrackAction(
                $command->userId,
                $command->sourceLabel,
                new DateTimeImmutable()
            )
        );
    }
}
