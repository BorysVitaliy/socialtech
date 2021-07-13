<?php

declare(strict_types=1);

namespace App\Model\User\Repository;

use App\Model\User\Entity\User;
use App\Model\User\Repository\Contract\UserRepositoryInterface;
use App\Model\User\Repository\Hydrator\UserHydratorInterface;
use League\Flysystem\FilesystemException;
use League\Flysystem\FilesystemOperator;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\SerializerInterface;

class UserRepository implements UserRepositoryInterface
{
    private const PATTERN_FILE_AUTH = '%s.%s';

    private UserHydratorInterface $hydrator;

    private FilesystemOperator $authStorage;

    private SerializerInterface $serializer;

    private DecoderInterface $decoder;

    private string $format;

    public function __construct(
        FilesystemOperator $authStorage,
        UserHydratorInterface $hydrator,
        SerializerInterface $serializer,
        DecoderInterface $decoder,
        string $format
    ) {
        $this->hydrator = $hydrator;
        $this->authStorage = $authStorage;
        $this->serializer = $serializer;
        $this->decoder = $decoder;
        $this->format = $format;
    }

    /**
     * @param string $nickName
     * @return User|null
     * @throws FilesystemException
     */
    public function getByNickName(string $nickName): ?User
    {
        if (!$this->existNickName($nickName)) {
            return null;
        }

        $userData = $this->authStorage->read(
            $this->getUserFileName($nickName)
        );

        $data = $this->decoder->decode($userData, $this->format);

        return $this->hydrator->hydrate($data);
    }

    /**
     * @param string $nickName
     * @return bool
     * @throws FilesystemException
     */
    public function existNickName(string $nickName): bool
    {
        return $this->authStorage->fileExists($this->getUserFileName($nickName));
    }

    /**
     * @param User $user
     * @throws FilesystemException
     */
    public function persist(User $user): void
    {
        $userData = $this->hydrator->extract($user);
        $this->authStorage->write(
            $this->getUserFileName($user->getNickName()),
            $this->serializer->serialize($userData, $this->format)
        );
    }

    private function getUserFileName(string $nickName): string
    {
        return sprintf(
            self::PATTERN_FILE_AUTH,
            $nickName,
            $this->format
        );
    }
}
