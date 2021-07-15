<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\Action\Track;

use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min=4)
     */
    public string $userId;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min=2)
     */
    public string $sourceLabel;
}
