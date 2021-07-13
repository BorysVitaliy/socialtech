<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\Registration\Request;

use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /**
     * @var string
     * @Assert\NotBlank()
     */
    public string $firstName;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    public string $lastName;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min=4)
     */
    public string $nickName;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min=6)
     */
    public string $password;
}
