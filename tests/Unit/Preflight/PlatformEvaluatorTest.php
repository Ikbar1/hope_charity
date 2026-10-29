<?php

namespace Tests\Unit\Preflight;

use App\Support\Preflight\CheckStatus;
use App\Support\Preflight\PlatformEvaluator;
use App\Support\Preflight\RuntimeSnapshot;
use PHPUnit\Framework\TestCase;

final class PlatformEvaluatorTest extends TestCase
{
    public function test_a_patched_windows_runtime_passes_all_platform_checks(): void
    {
        $results = (new PlatformEvaluator)->evaluate($this->runtime());

        self::assertSame(
            [CheckStatus::Pass, CheckStatus::Pass, CheckStatus::Pass, CheckStatus::Pass],
            array_column($results, 'status'),
        );
    }

    public function test_unsafe_configuration_fails_with_distinct_reasons(): void
    {
        $runtime = $this->runtime(
            extensions: array_values(array_diff(PlatformEvaluator::REQUIRED_EXTENSIONS, ['intl'])),
            uploadProgress: '1',
            provenance: ['php' => ['version' => '8.4.27', 'package_revision' => 0]],
        );
        $results = (new PlatformEvaluator)->evaluate($runtime);
        $statuses = [];

        foreach ($results as $result) {
            $statuses[$result->id] = $result->status;
        }

        self::assertSame(CheckStatus::Fail, $statuses['platform.provenance']);
        self::assertSame(CheckStatus::Fail, $statuses['php.extensions']);
        self::assertSame(CheckStatus::Fail, $statuses['php.session_upload_progress']);
    }

    public function test_missing_provenance_is_unverified_instead_of_claimed_safe(): void
    {
        $results = (new PlatformEvaluator)->evaluate($this->runtime(provenance: null));

        self::assertSame(CheckStatus::Unverified, $results[1]->status);
    }

    public function test_non_windows_vendor_without_patch_evidence_is_unverified(): void
    {
        $results = (new PlatformEvaluator)->evaluate($this->runtime(
            osFamily: 'Linux',
            provenance: ['vendor' => ['name' => 'Hosting Vendor', 'package' => 'php-8.4.27']],
        ));

        self::assertSame(CheckStatus::Unverified, $results[1]->status);
    }

    private function runtime(
        string $osFamily = 'Windows',
        array $extensions = PlatformEvaluator::REQUIRED_EXTENSIONS,
        string|false $uploadProgress = '0',
        ?array $provenance = ['php' => ['version' => '8.4.27', 'package_revision' => 1]],
    ): RuntimeSnapshot {
        return new RuntimeSnapshot(
            phpVersion: '8.4.27',
            osFamily: $osFamily,
            sapi: 'cli',
            loadedExtensions: $extensions,
            sessionUploadProgress: $uploadProgress,
            provenance: $provenance,
        );
    }
}
