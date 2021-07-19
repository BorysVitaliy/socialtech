<?php

declare(strict_types=1);

namespace App\Model\User\Storage;

use App\Model\User\Storage\Contract\UserStorageInterface;
use League\Flysystem\FilesystemException;
use League\Flysystem\FilesystemOperator;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\SerializerInterface;

class UserFileStorage implements UserStorageInterface
{
    private const PATTERN_FILE_AUTH = '%s.%s';

    private FilesystemOperator $authStorage;

    private DecoderInterface $decoder;

    private SerializerInterface $serializer;

    private string $formatFile;

    public function __construct(
        FilesystemOperator $authStorage,
        DecoderInterface $decoder,
        SerializerInterface $serializer,
        string $formatFile
    ) {
        $this->authStorage = $authStorage;
        $this->decoder = $decoder;
        $this->serializer = $serializer;
        $this->formatFile = $formatFile;
    }

    /**
     * @param string $nickName
     * @return array
     * @throws FilesystemException
     */
    public function get(string $nickName): array
    {
        $userData = $this->authStorage->read(
            $this->getUserFileName($nickName)
        );

        return $this->decoder->decode($userData, $this->formatFile);
    }

    /**
     * @param string $nickName
     * @param array $userData
     * @throws FilesystemException
     */
    public function store(string $nickName, array $userData): void
    {
        $this->authStorage->write(
            $this->getUserFileName($nickName),
            $this->serializer->serialize($userData, $this->formatFile)
        );
    }

    /**
     * @param string $nickName
     * @return bool
     * @throws FilesystemException
     */
    public function exist(string $nickName): bool
    {
        return $this->authStorage->fileExists(
            $this->getUserFileName($nickName)
        );
    }

    private function getUserFileName(string $nickName): string
    {
        return sprintf(
            self::PATTERN_FILE_AUTH,
            $nickName,
            $this->formatFile
        );
    }
}
