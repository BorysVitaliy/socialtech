<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\Registration\Request;

use App\Model\User\Entity\Id;
use App\Model\User\Entity\Name;
use App\Model\User\Entity\User;
use App\Model\User\Repository\Contract\UserRepositoryInterface;
use App\Model\User\Service\Contract\PasswordHasherInterface;
use DateTimeImmutable;
use DomainException;
use Exception;
use Psr\Log\LoggerInterface;

class Handler
{
    private const PATTERN_MSG_SUCCEED_CREATED = 'User %s was created';

    private UserRepositoryInterface $users;
    private PasswordHasherInterface $hasher;
    private LoggerInterface $logger;

    public function __construct(
        UserRepositoryInterface $users,
        PasswordHasherInterface $hasher,
        LoggerInterface $logger
    ) {
        $this->users = $users;
        $this->hasher = $hasher;
        $this->logger = $logger;
    }

    /**
     * @param Command $command
     * @throws Exception
     * @throws DomainException
     */
    public function handle(Command $command): void
    {
        if ($this->users->existNickName($command->nickName)) {
            throw new DomainException('User already exists.');
        }

        $user = new User(
            Id::next(),
            $command->nickName,
            new DateTimeImmutable(),
            new Name(
                $command->firstName,
                $command->lastName
            ),
            $this->hasher->hash($command->password)
        );

        $this->users->save($user);

        $this->logger->info(
            sprintf(self::PATTERN_MSG_SUCCEED_CREATED, $user->getNickName())
        );
    }
}
