<?php

declare(strict_types=1);

namespace App\Queue\Consumer\User;

use App\Model\User\Storage\Contract\TrackerStorageInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use App\Queue\Message\User\TrackAction;

class TrackActionHandler implements MessageHandlerInterface
{
    private NormalizerInterface $normalizer;

    private TrackerStorageInterface $storage;

    private LoggerInterface $logger;

    public function __construct(
        TrackerStorageInterface $storage,
        NormalizerInterface $normalizer,
        LoggerInterface $logger
    ) {
        $this->storage = $storage;
        $this->normalizer = $normalizer;
        $this->logger = $logger;
    }

    /**
     * @param TrackAction $message
     * @throws ExceptionInterface
     */
    public function __invoke(TrackAction $message)
    {
        $this->storage->store($this->normalizer->normalize($message));

        $this->logger->info('New User Event', [
            'userId' => $message->getUserId(),
            'source' => $message->getSourceLabel(),
        ]);
    }
}
