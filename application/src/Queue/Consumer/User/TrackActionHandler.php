<?php

declare(strict_types=1);

namespace App\Queue\Consumer\User;

use Psr\Log\LoggerInterface;
use App\Queue\Message\Message\TrackAction;
use SocialTech\StorageInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class TrackActionHandler implements MessageHandlerInterface
{
    private LoggerInterface $logger;

    private StorageInterface $storage;

    private SerializerInterface $serializer;

    private string $trackStoragePath;

    private string $formatStorage;

    private string $trackElementDelimiter;

    public function __construct(
        StorageInterface $storage,
        SerializerInterface $serializer,
        LoggerInterface $logger,
        string $trackStoragePath,
        string $formatStorage,
        string $trackElementDelimiter
    ) {
        $this->logger = $logger;
        $this->storage = $storage;
        $this->serializer = $serializer;
        $this->trackStoragePath = $trackStoragePath;
        $this->formatStorage = $formatStorage;
        $this->trackElementDelimiter = $trackElementDelimiter;
    }

    public function __invoke(TrackAction $message)
    {
        $content = $this->serializer->serialize($message, $this->formatStorage);

        if ($this->storage->exists($this->trackStoragePath)) {
            $this->storage->append($this->trackStoragePath, $this->trackElementDelimiter . $content);
        } else {
            $this->storage->store($this->trackStoragePath, $content);
        }

        $this->logger->info('New User Event', [
            'userId' => $message->getUserId(),
            'source' => $message->getSourceLabel(),
        ]);
    }
}
