<?php

namespace Tests\Unit;

use App\Support\Environment\TestDatabaseGuard;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use RuntimeException;

final class TestDatabaseGuardTest extends TestCase
{
    private const FORBIDDEN = ['web_yayasan', 'web_sekolah'];

    public function test_it_accepts_the_isolated_hope_charity_test_environment(): void
    {
        TestDatabaseGuard::assertIsolated($this->validEnvironment(), self::FORBIDDEN);

        $this->addToAssertionCount(1);
    }

    #[DataProvider('unsafeEnvironmentProvider')]
    public function test_it_rejects_an_environment_before_a_test_can_write_data(array $overrides): void
    {
        $this->expectException(RuntimeException::class);

        TestDatabaseGuard::assertIsolated(array_replace($this->validEnvironment(), $overrides), self::FORBIDDEN);
    }

    public static function unsafeEnvironmentProvider(): array
    {
        return [
            'wrong project id' => [['APP_PROJECT_ID' => 'web-yayasan']],
            'database from another project' => [['DB_DATABASE' => 'web_yayasan_testing']],
            'persistent database' => [['DB_DATABASE' => 'hope_charity_testing']],
            'cache from another project' => [['CACHE_PREFIX' => 'web_sekolah_cache_']],
            'storage from another project' => [['FILESYSTEM_PREFIX' => 'web_yayasan']],
        ];
    }

    private function validEnvironment(): array
    {
        return [
            'APP_PROJECT_ID' => 'hope-charity',
            'DB_DATABASE' => ':memory:',
            'CACHE_PREFIX' => 'hope_charity_test_cache_',
            'FILESYSTEM_PREFIX' => 'hope-charity-test',
        ];
    }
}
