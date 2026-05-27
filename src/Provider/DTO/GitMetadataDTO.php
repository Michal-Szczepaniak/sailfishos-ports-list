<?php

declare(strict_types=1);

namespace App\Provider\DTO;

final class GitMetadataDTO
{
    private ?string $author = null;

    private ?string $authorEmail = null;

    private ?string $device = null;

    private array $broken = [];

    private array $features = [];

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(?string $author): void
    {
        $this->author = $author;
    }

    public function getAuthorEmail(): ?string
    {
        return $this->authorEmail;
    }

    public function setAuthorEmail(?string $authorEmail): void
    {
        $this->authorEmail = $authorEmail;
    }

    public function getDevice(): ?string
    {
        return $this->device;
    }

    public function setDevice(?string $device): void
    {
        $this->device = $device;
    }

    public function getBroken(): array
    {
        return $this->broken;
    }

    public function setBroken(array $broken): void
    {
        $this->broken = $broken;
    }

    public function getFeatures(): array
    {
        return $this->features;
    }

    public function setFeatures(array $features): void
    {
        $this->features = $features;
    }
}
