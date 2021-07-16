<?php

declare(strict_types=1);

namespace App\Security;

use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use App\ReadModel\User\UserFetcher;
use App\ReadModel\User\AuthView;

class UserProvider implements UserProviderInterface
{
    private UserFetcher $userFetcher;

    public function __construct(UserFetcher $userFetcher)
    {
        $this->userFetcher = $userFetcher;
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        return $this->loadUserByUsername($identifier);
    }

    public function loadUserByUsername($username): UserInterface
    {
        $user = $this->loadUser($username);

        return self::identityByUser($user, $username);
    }

    /**
     * @param UserInterface $identity
     * @throws UnsupportedUserException
     * @return UserInterface
     */
    public function refreshUser(UserInterface $identity): UserInterface
    {
        $user = $this->loadUser($identity->getUserIdentifier());

        return self::identityByUser($user, $identity->getUsername());
    }

    public function supportsClass($class): bool
    {
        return $class === User::class;
    }

    /**
     * @param $username
     * @throws UserNotFoundException
     * @return AuthView
     */
    private function loadUser($username): AuthView
    {
        if ($user = $this->userFetcher->findForAuthByUserName($username)) {
            return $user;
        }

        throw new UserNotFoundException('User not found');
    }

    private static function identityByUser(AuthView $user, string $username): User
    {
        return new User(
            $user->id,
            $username,
            $user->passwordHash
        );
    }
}
