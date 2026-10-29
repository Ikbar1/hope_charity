<?php

namespace App\Support\Preflight;

use JsonException;
use Symfony\Component\Process\Process;

final readonly class PreflightRunner
{
    public function __construct(private string $projectRoot) {}

    public function run(bool $runOperationalChecks = false): PreflightReport
    {
        $provenance = $this->readProvenance();
        $runtime = new RuntimeSnapshot(
            phpVersion: PHP_VERSION,
            osFamily: PHP_OS_FAMILY,
            sapi: PHP_SAPI,
            loadedExtensions: get_loaded_extensions(),
            sessionUploadProgress: ini_get('session.upload_progress.enabled'),
            provenance: $provenance,
        );
        $checks = (new PlatformEvaluator)->evaluate($runtime);
        $checks[] = (new ProjectIsolationEvaluator)->evaluate([
            'APP_PROJECT_ID' => (string) config('hope.project_id'),
            'DB_DATABASE' => (string) config('database.connections.'.config('database.default').'.database'),
            'CACHE_PREFIX' => (string) config('cache.prefix'),
            'FILESYSTEM_PREFIX' => (string) env('FILESYSTEM_PREFIX', 'hope-charity'),
        ], config('hope.forbidden_identifiers'));
        $checks[] = new CheckResult(
            'runtime.context',
            CheckStatus::Pass,
            'Konteks runtime CLI berhasil dibaca.',
            ['php_version' => PHP_VERSION, 'sapi' => PHP_SAPI],
        );

        if ($runOperationalChecks) {
            array_push($checks, ...$this->operationalChecks());
        } else {
            $checks[] = new CheckResult('composer.platform', CheckStatus::Unverified, 'Composer platform check belum dijalankan.');
            $checks[] = new CheckResult('application.tests', CheckStatus::Unverified, 'Test aplikasi belum dijalankan oleh preflight.');
            $checks[] = new CheckResult('vite.manifest', CheckStatus::Unverified, 'Manifest Vite belum diperiksa oleh preflight.');
        }

        return new PreflightReport($checks);
    }

    /** @return list<CheckResult> */
    private function operationalChecks(): array
    {
        $composer = $this->runCommand([
            PHP_BINARY,
            $this->projectRoot.'/.local/runtime/composer/composer.phar',
            'check-platform-reqs',
        ]);
        $tests = $this->runCommand(
            [PHP_BINARY, $this->projectRoot.'/artisan', 'test', '--compact'],
            [
                'APP_ENV' => 'testing',
                'APP_PROJECT_ID' => 'hope-charity',
                'APP_KEY' => 'base64:AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA=',
                'CACHE_STORE' => 'array',
                'CACHE_PREFIX' => 'hope_charity_test_cache_',
                'DB_CONNECTION' => 'sqlite',
                'DB_DATABASE' => ':memory:',
                'FILESYSTEM_PREFIX' => 'hope-charity-test',
                'MAIL_MAILER' => 'array',
                'QUEUE_CONNECTION' => 'sync',
                'SESSION_DRIVER' => 'array',
            ],
        );
        $manifest = $this->evaluateViteManifest();

        return [
            new CheckResult(
                'composer.platform',
                $composer ? CheckStatus::Pass : CheckStatus::Fail,
                $composer ? 'Composer platform requirements terpenuhi.' : 'Composer platform requirements gagal.',
            ),
            new CheckResult(
                'application.tests',
                $tests ? CheckStatus::Pass : CheckStatus::Fail,
                $tests ? 'Test aplikasi lulus.' : 'Test aplikasi gagal.',
            ),
            $manifest,
        ];
    }

    /**
     * @param  list<string>  $command
     * @param  array<string, string>  $environment
     */
    private function runCommand(array $command, array $environment = []): bool
    {
        foreach ($command as $argument) {
            if ($argument === '' || (str_contains($argument, '/') && ! file_exists($argument))) {
                return false;
            }
        }

        $process = new Process($command, $this->projectRoot, array_merge([
            'GIT_CONFIG_COUNT' => '1',
            'GIT_CONFIG_KEY_0' => 'safe.directory',
            'GIT_CONFIG_VALUE_0' => str_replace('\\', '/', $this->projectRoot),
        ], $environment));
        $process->setTimeout(120);
        $process->run();

        return $process->isSuccessful();
    }

    private function evaluateViteManifest(): CheckResult
    {
        $manifestPath = $this->projectRoot.'/public/build/manifest.json';

        if (! is_file($manifestPath)) {
            return new CheckResult('vite.manifest', CheckStatus::Fail, 'Manifest Vite tidak tersedia.');
        }

        try {
            $manifest = json_decode((string) file_get_contents($manifestPath), true, flags: JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            return new CheckResult('vite.manifest', CheckStatus::Fail, 'Manifest Vite bukan JSON yang valid.');
        }

        foreach (['resources/css/app.css', 'resources/js/app.js'] as $entry) {
            if (! isset($manifest[$entry]['file'])) {
                return new CheckResult('vite.manifest', CheckStatus::Fail, 'Manifest Vite tidak memuat seluruh entry aplikasi.');
            }
        }

        return new CheckResult('vite.manifest', CheckStatus::Pass, 'Manifest Vite memuat seluruh entry aplikasi.');
    }

    private function readProvenance(): ?array
    {
        $path = $this->projectRoot.'/.local/runtime/provenance.json';

        if (! is_file($path)) {
            return null;
        }

        try {
            $value = json_decode((string) file_get_contents($path), true, flags: JSON_THROW_ON_ERROR);

            return is_array($value) ? $value : null;
        } catch (JsonException) {
            return null;
        }
    }
}
