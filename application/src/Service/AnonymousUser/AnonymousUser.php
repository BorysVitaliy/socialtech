<?php

declare(strict_types=1);

namespace App\Service\AnonymousUser;

use App\Service\AnonymousUser\Contract\AnonymousUserInterface;
use App\Service\AnonymousUser\Exception\NotFoundAnonymousUser;
use App\Service\Hasher\Contract\HasherInterface;
use League\Flysystem\FilesystemException;
use League\Flysystem\FilesystemOperator;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Serializer\SerializerInterface;

class AnonymousUser implements AnonymousUserInterface
{
    private FilesystemOperator $anonymousUserStorage;

    private SerializerInterface $serializer;

    private HasherInterface $hasher;

    private LoggerInterface $logger;

    private string $formatFile;

    public function __construct(
        FilesystemOperator $anonymousUserStorage,
        SerializerInterface $serializer,
        HasherInterface $hasher,
        LoggerInterface $logger,
        string $formatFile
    ) {
        $this->anonymousUserStorage = $anonymousUserStorage;
        $this->serializer = $serializer;
        $this->hasher = $hasher;
        $this->logger = $logger;
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
        $uuid =  $this->createUuid($ip, $userAgent);

        $this->anonymousUserStorage->write($this->getAnonymousUserFileName($uuid), $uuid);

        $this->logger->info('Anonymous user was succeed created', [
            'id' => $uuid,
            'ip' => $ip,
            'userAgent' => $userAgent
        ]);

        return $uuid;
    }

    /**
     * @param string $uuid
     * @throws NotFoundAnonymousUser
     * @throws FilesystemException
     */
    public function ensureExistingUuid(string $uuid): void
    {
        $userExists = $this->anonymousUserStorage->fileExists(
            $this->getAnonymousUserFileName($uuid)
        );

        if (!$userExists) {
            throw new NotFoundAnonymousUser($uuid);
        }
    }

    private function createUuid(string $ip, string $userAgent): string
    {
        return Uuid::fromString($this->hashUserData($ip, $userAgent))->toString();
    }

    private function hashUserData(string $ip, string $userAgent): string
    {
        $userData = [$ip, $userAgent];

        return $this->hasher->hash(
            $this->serializer->serialize($userData, $this->formatFile)
        );
    }

    private function getAnonymousUserFileName(string $uuid): string
    {
        return $uuid . '.' . $this->formatFile;
    }
}
