<?php

namespace App\Support\Environment;

use RuntimeException;

final class TestDatabaseGuard
{
    public const PROJECT_ID = 'hope-charity';

    /**
     * @param  array<string, string|null>  $environment
     * @param  list<string>  $forbiddenIdentifiers
     */
    public static function assertIsolated(array $environment, array $forbiddenIdentifiers): void
    {
        $projectId = strtolower(trim((string) ($environment['APP_PROJECT_ID'] ?? '')));

        if ($projectId !== self::PROJECT_ID) {
            throw new RuntimeException('Test environment is not identified as the Hope Charity project.');
        }

        $isolatedValues = [
            'DB_DATABASE' => $environment['DB_DATABASE'] ?? null,
            'CACHE_PREFIX' => $environment['CACHE_PREFIX'] ?? null,
            'FILESYSTEM_PREFIX' => $environment['FILESYSTEM_PREFIX'] ?? null,
        ];

        foreach ($isolatedValues as $name => $value) {
            $normalized = strtolower((string) $value);

            foreach ($forbiddenIdentifiers as $forbidden) {
                if (self::containsIdentifier($normalized, $forbidden)) {
                    throw new RuntimeException("{$name} references a different project.");
                }
            }
        }

        if (($isolatedValues['DB_DATABASE'] ?? '') !== ':memory:') {
            throw new RuntimeException('Tests that may write data must use the isolated in-memory database.');
        }

        if (! str_starts_with((string) ($isolatedValues['CACHE_PREFIX'] ?? ''), 'hope_charity_test_')) {
            throw new RuntimeException('The test cache namespace is not isolated for Hope Charity.');
        }

        if (($isolatedValues['FILESYSTEM_PREFIX'] ?? '') !== 'hope-charity-test') {
            throw new RuntimeException('The test storage namespace is not isolated for Hope Charity.');
        }
    }

    private static function containsIdentifier(string $value, string $identifier): bool
    {
        if ($identifier === '') {
            return false;
        }

        return preg_match(
            '/(?<![a-z0-9])'.preg_quote(strtolower($identifier), '/').'(?![a-z0-9])/i',
            $value,
        ) === 1;
    }
}
