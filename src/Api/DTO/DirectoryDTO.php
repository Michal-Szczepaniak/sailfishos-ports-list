<?php

declare(strict_types=1);

namespace App\Api\DTO;

final class DirectoryDTO
{
    /** @var EntryDTO[] */
    private array $entries = [];

    public function getEntries(): array
    {
        return $this->entries;
    }

    public function setEntries(array $entries): void
    {
        $this->entries = $entries;
    }

    public function addItem(EntryDTO $entry): void
    {
        $this->entries[] = $entry;
    }

    public function clearEntries(): void
    {
        $this->entries = [];
    }
}
