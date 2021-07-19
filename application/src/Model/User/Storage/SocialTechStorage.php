<?php

declare(strict_types=1);

namespace App\Model\User\Storage;

use App\Model\User\Storage\Contract\TrackerStorageInterface;
use SocialTech\StorageInterface;
use Symfony\Component\Serializer\SerializerInterface;

class SocialTechStorage implements TrackerStorageInterface
{
    private StorageInterface $socialStorage;

    private SerializerInterface $serializer;

    private string $dataFormat;

    private string $trackStoragePath;

    private string $trackElementDelimiter;

    public function __construct(
        StorageInterface $socialStorage,
        SerializerInterface $serializer,
        string $dataFormat,
        string $trackStoragePath,
        string $trackElementDelimiter
    ) {
        $this->socialStorage = $socialStorage;
        $this->serializer = $serializer;
        $this->dataFormat = $dataFormat;
        $this->trackStoragePath = $trackStoragePath;
        $this->trackElementDelimiter = $trackElementDelimiter;
    }

    public function store(array $content): void
    {
        $contentAsString = $this->serializer->serialize($content, $this->dataFormat);

        if ($this->socialStorage->exists($this->trackStoragePath)) {
            $this->append($contentAsString);
        } else {
            $this->add($contentAsString);
        }
    }

    private function add(string $content): void
    {
        $this->socialStorage->store($this->trackStoragePath, $content);
    }

    private function append(string $content): void
    {
        $content .= $this->trackElementDelimiter . $content;
        $this->socialStorage->append(
            $this->trackStoragePath,
            $content
        );
    }
}
