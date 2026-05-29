<?php

declare(strict_types=1);

namespace App\Provider\DTO;

final class GitMetadataDTO
{
    private ?array $authors = null;

    private ?array $authorEmails = null;

    private ?string $device = null;

    private ?string $url = null;

    private array $broken = [];

    private array $features = [];

    public function getAuthors(): ?array
    {
        return $this->authors;
    }

    public function setAuthors(?array $authors): void
    {
        $this->authors = $authors;
    }

    public function getAuthorEmails(): ?array
    {
        return $this->authorEmails;
    }

    public function setAuthorEmails(?array $authorEmails): void
    {
        $this->authorEmails = $authorEmails;
    }

    public function getDevice(): ?string
    {
        return $this->device;
    }

    public function setDevice(?string $device): void
    {
        $this->device = $device;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): void
    {
        $this->url = $url;
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
