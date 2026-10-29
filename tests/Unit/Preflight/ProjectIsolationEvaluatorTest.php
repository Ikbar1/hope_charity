<?php

namespace Tests\Unit\Preflight;

use App\Support\Preflight\CheckStatus;
use App\Support\Preflight\ProjectIsolationEvaluator;
use PHPUnit\Framework\TestCase;

final class ProjectIsolationEvaluatorTest extends TestCase
{
    public function test_it_accepts_the_web_yayasan2_workspace_name_without_confusing_it_for_the_reference_project(): void
    {
        $result = (new ProjectIsolationEvaluator)->evaluate([
            'APP_PROJECT_ID' => 'hope-charity',
            'DB_DATABASE' => 'D:\\FreeWork\\web_yayasan2\\database\\hope-charity.sqlite',
            'CACHE_PREFIX' => 'hope_charity_cache_',
            'FILESYSTEM_PREFIX' => 'hope-charity',
        ], ['web_yayasan', 'web_sekolah']);

        self::assertSame(CheckStatus::Pass, $result->status);
    }

    public function test_it_rejects_a_database_identifier_from_another_project(): void
    {
        $result = (new ProjectIsolationEvaluator)->evaluate([
            'APP_PROJECT_ID' => 'hope-charity',
            'DB_DATABASE' => 'web_yayasan',
            'CACHE_PREFIX' => 'hope_charity_cache_',
            'FILESYSTEM_PREFIX' => 'hope-charity',
        ], ['web_yayasan', 'web_sekolah']);

        self::assertSame(CheckStatus::Fail, $result->status);
    }
}
