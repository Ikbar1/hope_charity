<?php

namespace Tests\Unit\Preflight;

use App\Support\Preflight\CheckResult;
use App\Support\Preflight\CheckStatus;
use App\Support\Preflight\PreflightReport;
use PHPUnit\Framework\TestCase;

final class PreflightReportTest extends TestCase
{
    public function test_json_and_human_output_never_expose_fixture_credentials_or_private_paths(): void
    {
        $report = new PreflightReport([
            new CheckResult('fixture', CheckStatus::Fail, 'Credential fixture ditolak.', [
                'password' => 'super-secret-value',
                'dsn' => 'mysql://admin:hunter2@example.test/database',
                'message' => 'Failure in C:\\Users\\Ikbar\\private\\config.ini',
            ]),
        ]);

        $json = json_encode($report, JSON_THROW_ON_ERROR);
        $human = $report->toHuman();

        self::assertStringNotContainsString('super-secret-value', $json);
        self::assertStringNotContainsString('hunter2', $json);
        self::assertStringNotContainsString('Ikbar', $json);
        self::assertStringNotContainsString('super-secret-value', $human);
        self::assertStringNotContainsString('hunter2', $human);
        self::assertStringNotContainsString('Ikbar', $human);
    }
}
