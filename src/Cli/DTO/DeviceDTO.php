<?php

declare(strict_types=1);

namespace App\Cli\DTO;

final class DeviceDTO
{
    private string $name;

    private string $vendor;

    private string $version;

    private string $project;

    public function __construct(string $name, string $vendor, string $version, string $project)
    {
        $this->name = $name;
        $this->vendor = $vendor;
        $this->version = $version;
        $this->project = $project;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getVendor(): string
    {
        return $this->vendor;
    }

    public function setVendor(string $vendor): void
    {
        $this->vendor = $vendor;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function setVersion(string $version): void
    {
        $this->version = $version;
    }

    public function getProject(): string
    {
        return $this->project;
    }

    public function setProject(string $project): void
    {
        $this->project = $project;
    }
}
