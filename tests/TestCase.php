<?php

namespace Tests;

use App\Support\Environment\TestDatabaseGuard;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        TestDatabaseGuard::assertIsolated([
            'APP_PROJECT_ID' => env('APP_PROJECT_ID'),
            'DB_DATABASE' => env('DB_DATABASE'),
            'CACHE_PREFIX' => env('CACHE_PREFIX'),
            'FILESYSTEM_PREFIX' => env('FILESYSTEM_PREFIX'),
        ], ['web_yayasan', 'web-yayasan', 'web_sekolah', 'web-sekolah']);

        parent::setUp();
    }
}
