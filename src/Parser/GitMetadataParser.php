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

    public function parse(string $content): ?GitMetadataDTO
    {
        if ($content === '') return null;

        $metadata = new GitMetadataDTO();
        $lines = preg_split('/\R/', $content);
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

            $values = array_map(
                fn (string $val) => preg_replace('/[^a-z0-9.,\s_-]/i', '', trim($val)),
                explode(',', $value, 5)
            );
            $values = array_values(array_filter($values, fn($v) => $v !== ''));
            $value = preg_replace('/[^a-z0-9@.,\s_-]/i', '', $value);

            match (strtoupper($key)) {
                self::AUTHOR_KEY => $metadata->setAuthor($value),
                self::AUTHOR_EMAIL_KEY => $metadata->setAuthorEmail($value),
                self::BROKEN_KEY => $metadata->setBroken($values),
                self::FEATURES_KEY => $metadata->setFeatures($values),
                self::DEVICE_KEY => $metadata->setDevice($value),
            };
        }

        return $metadata;
    }
}
