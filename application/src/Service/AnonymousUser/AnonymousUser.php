<?php

declare(strict_types=1);

namespace App\Service\AnonymousUser;

use App\Service\AnonymousUser\Exception\NotFoundAnonymousUser;
use League\Flysystem\FilesystemException;
use League\Flysystem\FilesystemOperator;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Serializer\SerializerInterface;

class AnonymousUser implements AnonymousUserInterface
{
    private LoggerInterface $logger;

    private FilesystemOperator $anonymousUserStorage;

    private SerializerInterface $serializer;

    private string $formatFile;

    public function __construct(
        FilesystemOperator $anonymousUserStorage,
        SerializerInterface $serializer,
        LoggerInterface $logger,
        string $formatFile
    ) {
        $this->logger = $logger;
        $this->anonymousUserStorage = $anonymousUserStorage;
        $this->serializer = $serializer;
        $this->formatFile = $formatFile;
    }

    /**
     * @param string $ip
     * @param string $userAgent
     * @return string
     * @throws FilesystemException
     */
    public function create(string $ip, string $userAgent): string
    {
        $uuid =  Uuid::fromString(
            $this->prepareUiidPartsString($ip, $userAgent)
        )->toString();

        $this->logger->info('Anonymous user was succeed created', [
            'id' => $uuid,
            'ip' => $ip,
            'userAgent' => $userAgent
        ]);

        $this->anonymousUserStorage->write($this->getAnonymousUserFileName($uuid), $uuid);

        return $uuid;
    }

    /**
     * @param string $uuid
     * @return string
     * @throws FilesystemException
     */
    public function getId(string $uuid): string
    {
        $userExists = $this->anonymousUserStorage->fileExists(
            $this->getAnonymousUserFileName($uuid)
        );

        if (!$userExists) {
            throw new NotFoundAnonymousUser($uuid);
        }

        return $uuid;
    }

    private function prepareUiidPartsString(string $ip, string $userAgent): string
    {
        $uuidParts = [$ip, $userAgent, microtime(true)];

        return md5(
            $this->serializer->serialize($uuidParts, $this->formatFile)
        );
    }

    private function getAnonymousUserFileName(string $uuid): string
    {
        return $uuid . '.' . $this->formatFile;
    }
}
