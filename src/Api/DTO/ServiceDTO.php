<?php

declare(strict_types=1);

namespace App\Api\DTO;

final class ServiceDTO
{
    private string $name;

    /** @var ParamDTO[] */
    private array $params = [];

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public function setParams(array $params): void
    {
        $this->params = $params;
    }

    public function addItem(ParamDTO $param): void
    {
        $this->params[] = $param;
    }

    public function clearParams(): void
    {
        $this->params = [];
    }
}
