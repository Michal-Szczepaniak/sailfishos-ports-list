<?php

declare(strict_types=1);

namespace App\Api\DTO;

final class ServicesDTO
{
    /** @var ServiceDTO[] */
    private array $services = [];

    public function getServices(): array
    {
        return $this->services;
    }

    public function setServices(array $services): void
    {
        $this->services = $services;
    }

    public function addItem(ServiceDTO $service): void
    {
        $this->services[] = $service;
    }

    public function clearServices(): void
    {
        $this->services = [];
    }
}
