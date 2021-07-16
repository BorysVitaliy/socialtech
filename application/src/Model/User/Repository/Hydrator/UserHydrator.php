<?php

declare(strict_types=1);

namespace App\Model\User\Repository\Hydrator;

use App\Model\User\Entity\Id;
use App\Model\User\Entity\Name;
use App\Model\User\Entity\User;
use DateTimeImmutable;
use Exception;

class UserHydrator implements UserHydratorInterface
{
    private string $createdFormat;

    public function __construct(string $createdFormat)
    {
        $this->createdFormat = $createdFormat;
    }

    /**
     * @param array $user
     * @return User
     * @throws Exception
     */
    public function hydrate(array $user): User
    {
        return new User(
            new Id($user['id']),
            $user['nickName'],
            new DateTimeImmutable($user['createdAt']),
            new Name(
                $user['firstName'],
                $user['lastName']
            ),
            $user['password']
        );
    }

    public function extract(User $user): array
    {
        return [
            'id' => $user->getId()->getValue(),
            'nickName' => $user->getNickName(),
            'firstName' => $user->getName()->getFirst(),
            'lastName' => $user->getName()->getLast(),
            'password' => $user->getPasswordHash(),
            'createdAt' => $user->getCreatedAt()->format($this->createdFormat)
        ];
    }
}
