<?php

declare(strict_types=1);

namespace App\Parser;

use App\Provider\DTO\GitMetadataDTO;

final class GitMetadataParser
{
    private const string AUTHOR_KEY = 'AUTHOR';

    private const string AUTHOR_EMAIL_KEY = 'EMAIL';

    private const string BROKEN_KEY = 'BROKEN';

    private const string FEATURES_KEY = 'FEATURES';

    private const string DEVICE_KEY = 'DEVICE';

    private const string URL_KEY = 'URL';

    public function parse(string $metadataFile, string $specFile): ?GitMetadataDTO
    {
        $metadata = null;

        if ($specFile !== '') {
            $metadata ??= new GitMetadataDTO();
            $this->parseSpec($metadata, $specFile);
        }

        if ($metadataFile !== '') {
            $metadata ??= new GitMetadataDTO();
            $this->parseMetadata($metadata, $metadataFile);
        }

        return $metadata;
    }

    private function parseSpec(GitMetadataDTO &$metadata, string $specFile): void
    {
        preg_match('/^%define\s+vendor_pretty\s+(.+)$/m', $specFile, $vendor);
        preg_match('/^%define\s+device_pretty\s+(.+)$/m', $specFile, $device);

        $metadata->setDevice($device[1] ?? null);
        $metadata->setVendor($vendor[1] ?? null);
    }

    private function parseMetadata(GitMetadataDTO &$metadata, string $metadataFile): void
    {
        $lines = preg_split('/\R/', $metadataFile);
        foreach ($lines as $line) {
            $line = trim($line);

            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }

            if (!str_contains($line, '=')) {
                continue;
            }

            [$key, $value] = explode('=', $line, 2);
            $key = preg_replace('/[^a-z0-9]/i', '', trim($key));
            $value = trim($value);
            $rawValue = $value;

            $values = array_map(
                fn (string $val) => preg_replace('/[^a-z0-9.,\s_-]/i', '', trim($val)),
                explode(',', $value, 5)
            );
            $values = array_values(array_filter($values, fn($v) => $v !== ''));
            $value = preg_replace('/[^a-z0-9@.,\s_-]/i', '', $value);

            match (strtoupper($key)) {
                self::AUTHOR_KEY => $metadata->setAuthors($values),
                self::AUTHOR_EMAIL_KEY => $metadata->setAuthorEmails($values),
                self::BROKEN_KEY => $metadata->setBroken($values),
                self::FEATURES_KEY => $metadata->setFeatures($values),
                self::DEVICE_KEY => $metadata->setDevice($value),
                self::URL_KEY => filter_var($rawValue, FILTER_VALIDATE_URL) ? $metadata->setUrl($rawValue) : '',
            };
        }
    }

}
