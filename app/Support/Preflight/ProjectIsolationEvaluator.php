<?php

namespace App\Support\Preflight;

final class ProjectIsolationEvaluator
{
    /**
     * @param  array<string, string|null>  $environment
     * @param  list<string>  $forbiddenIdentifiers
     */
    public function evaluate(array $environment, array $forbiddenIdentifiers): CheckResult
    {
        if (($environment['APP_PROJECT_ID'] ?? '') !== 'hope-charity') {
            return new CheckResult('project.isolation', CheckStatus::Fail, 'Identifier proyek bukan Hope Charity.');
        }

        foreach (['DB_DATABASE', 'CACHE_PREFIX', 'FILESYSTEM_PREFIX'] as $name) {
            $value = strtolower((string) ($environment[$name] ?? ''));

            foreach ($forbiddenIdentifiers as $forbidden) {
                if ($this->containsIdentifier($value, $forbidden)) {
                    return new CheckResult('project.isolation', CheckStatus::Fail, "{$name} merujuk proyek lain.");
                }
            }
        }

        if (! str_contains(strtolower((string) ($environment['CACHE_PREFIX'] ?? '')), 'hope_charity')) {
            return new CheckResult('project.isolation', CheckStatus::Fail, 'Namespace cache tidak khusus Hope Charity.');
        }

        if (! str_contains(strtolower((string) ($environment['FILESYSTEM_PREFIX'] ?? '')), 'hope-charity')) {
            return new CheckResult('project.isolation', CheckStatus::Fail, 'Namespace storage tidak khusus Hope Charity.');
        }

        $database = strtolower((string) ($environment['DB_DATABASE'] ?? ''));

        if ($database !== ':memory:' && ! str_contains($database, 'hope-charity') && ! str_contains($database, 'hope_charity')) {
            return new CheckResult('project.isolation', CheckStatus::Fail, 'Identifier database tidak khusus Hope Charity.');
        }

        return new CheckResult('project.isolation', CheckStatus::Pass, 'Identifier proyek dan namespace penyimpanan terisolasi.');
    }

    private function containsIdentifier(string $value, string $identifier): bool
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
