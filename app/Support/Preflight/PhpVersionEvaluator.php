<?php

namespace App\Support\Preflight;

final class PhpVersionEvaluator
{
    public const MINIMUM_VERSION = '8.4.27';

    public const MAXIMUM_EXCLUSIVE_VERSION = '9.0.0';

    public function evaluate(?string $version): CheckResult
    {
        $normalized = trim((string) $version);

        if ($normalized === '' || preg_match('/^\d+\.\d+\.\d+$/D', $normalized) !== 1) {
            return new CheckResult(
                'php.version',
                CheckStatus::Fail,
                'Versi PHP tidak diketahui atau bukan rilis stabil.',
            );
        }

        if (version_compare($normalized, self::MINIMUM_VERSION, '<')) {
            return new CheckResult(
                'php.version',
                CheckStatus::Fail,
                'Versi PHP lebih lama dari baseline keamanan minimum.',
                ['version' => $normalized, 'minimum' => self::MINIMUM_VERSION],
            );
        }

        if (version_compare($normalized, self::MAXIMUM_EXCLUSIVE_VERSION, '>=')) {
            return new CheckResult(
                'php.version',
                CheckStatus::Fail,
                'Versi mayor PHP belum termasuk rentang runtime yang disetujui.',
                ['version' => $normalized, 'maximum_exclusive' => self::MAXIMUM_EXCLUSIVE_VERSION],
            );
        }

        return new CheckResult(
            'php.version',
            CheckStatus::Pass,
            'Versi PHP stabil memenuhi baseline keamanan.',
            ['version' => $normalized],
        );
    }
}
