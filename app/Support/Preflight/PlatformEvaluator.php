<?php

namespace App\Support\Preflight;

final class PlatformEvaluator
{
    public const REQUIRED_EXTENSIONS = [
        'ctype',
        'curl',
        'dom',
        'exif',
        'fileinfo',
        'filter',
        'gd',
        'hash',
        'intl',
        'mbstring',
        'openssl',
        'pcre',
        'pdo',
        'session',
        'tokenizer',
        'xml',
    ];

    /** @return list<CheckResult> */
    public function evaluate(RuntimeSnapshot $runtime): array
    {
        return [
            (new PhpVersionEvaluator)->evaluate($runtime->phpVersion),
            $this->evaluateProvenance($runtime),
            $this->evaluateExtensions($runtime->loadedExtensions),
            $this->evaluateSessionUploadProgress($runtime->sessionUploadProgress),
        ];
    }

    private function evaluateProvenance(RuntimeSnapshot $runtime): CheckResult
    {
        if ($runtime->provenance === null) {
            return new CheckResult(
                'platform.provenance',
                CheckStatus::Unverified,
                'Metadata paket runtime belum tersedia.',
            );
        }

        if (strcasecmp($runtime->osFamily, 'Windows') === 0) {
            $php = $runtime->provenance['php'] ?? null;
            $revision = is_array($php) ? ($php['package_revision'] ?? null) : null;
            $version = is_array($php) ? ($php['version'] ?? null) : null;

            if (! is_int($revision) || $revision < 1 || $version !== $runtime->phpVersion) {
                return new CheckResult(
                    'platform.provenance',
                    CheckStatus::Fail,
                    'Build Windows bukan PHP 8.4.27-1+ yang dapat dibuktikan.',
                );
            }

            return new CheckResult(
                'platform.provenance',
                CheckStatus::Pass,
                'Build Windows dan revision paket memenuhi baseline.',
                ['platform' => 'windows', 'package_revision' => $revision],
            );
        }

        $vendor = $runtime->provenance['vendor'] ?? null;
        $required = ['name', 'package', 'security_patch_evidence'];

        if (! is_array($vendor) || array_any($required, fn (string $key): bool => trim((string) ($vendor[$key] ?? '')) === '')) {
            return new CheckResult(
                'platform.provenance',
                CheckStatus::Unverified,
                'Paket vendor non-Windows belum mempunyai bukti patch yang dapat direview.',
            );
        }

        return new CheckResult(
            'platform.provenance',
            CheckStatus::Pass,
            'Metadata paket vendor non-Windows dan bukti patch tersedia.',
            ['platform' => strtolower($runtime->osFamily), 'vendor' => $vendor['name']],
        );
    }

    /** @param list<string> $loadedExtensions */
    private function evaluateExtensions(array $loadedExtensions): CheckResult
    {
        $normalized = array_map('strtolower', $loadedExtensions);
        $missing = array_values(array_diff(self::REQUIRED_EXTENSIONS, $normalized));

        if ($missing !== []) {
            return new CheckResult(
                'php.extensions',
                CheckStatus::Fail,
                'Satu atau lebih ekstensi PHP wajib tidak tersedia.',
                ['missing' => $missing],
            );
        }

        return new CheckResult(
            'php.extensions',
            CheckStatus::Pass,
            'Seluruh ekstensi PHP wajib tersedia.',
            ['loaded' => self::REQUIRED_EXTENSIONS],
        );
    }

    private function evaluateSessionUploadProgress(string|false $value): CheckResult
    {
        $normalized = strtolower(trim((string) $value));

        if (in_array($normalized, ['', '0', 'off', 'false'], true)) {
            return new CheckResult(
                'php.session_upload_progress',
                CheckStatus::Pass,
                'Session upload progress dinonaktifkan pada konfigurasi runtime.',
                ['enabled' => false],
            );
        }

        return new CheckResult(
            'php.session_upload_progress',
            CheckStatus::Fail,
            'Session upload progress masih aktif pada konfigurasi runtime.',
            ['enabled' => true],
        );
    }
}
