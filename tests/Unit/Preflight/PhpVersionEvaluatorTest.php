<?php

namespace Tests\Unit\Preflight;

use App\Support\Preflight\CheckStatus;
use App\Support\Preflight\PhpVersionEvaluator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class PhpVersionEvaluatorTest extends TestCase
{
    #[DataProvider('versionProvider')]
    public function test_it_enforces_the_approved_stable_php_range(?string $version, CheckStatus $expected): void
    {
        $result = (new PhpVersionEvaluator)->evaluate($version);

        self::assertSame($expected, $result->status);
    }

    public static function versionProvider(): array
    {
        return [
            'PHP 8.3 is too old' => ['8.3.36', CheckStatus::Fail],
            'PHP 8.4.26 is vulnerable' => ['8.4.26', CheckStatus::Fail],
            'minimum fixed release' => ['8.4.27', CheckStatus::Pass],
            'newer stable build' => ['8.5.12', CheckStatus::Pass],
            '8.6 prerelease' => ['8.6.0RC1', CheckStatus::Fail],
            '9.0 boundary' => ['9.0.0', CheckStatus::Fail],
            'unknown version' => [null, CheckStatus::Fail],
            'unparseable version' => ['unknown', CheckStatus::Fail],
        ];
    }
}
