<?php

namespace Tests\Unit\PublicContent;

use App\Domain\PublicContent\Infrastructure\StaticContentValidator;
use DomainException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class StaticContentValidatorTest extends TestCase
{
    #[DataProvider('invalidProgramsProvider')]
    public function test_every_invalid_fixture_is_rejected_before_rendering(array $programs): void
    {
        $this->expectException(DomainException::class);

        (new StaticContentValidator)->validatePrograms($programs);
    }

    public static function invalidProgramsProvider(): array
    {
        $first = self::program('first', 1);
        $second = self::program('second', 2);

        return [
            'duplicate key' => [[$first, array_replace($second, ['key' => 'first'])]],
            'duplicate order' => [[$first, array_replace($second, ['order' => 1])]],
            'missing Indonesian locale' => [[array_replace_recursive($first, ['names' => ['id' => '']])]],
            'missing English locale' => [[array_replace_recursive($first, ['names' => ['en' => '']])]],
            'missing Arabic locale' => [[array_replace_recursive($first, ['names' => ['ar' => '']])]],
            'verified names without provenance' => [[array_replace($first, ['provenance' => ''])]],
            'factual description without field provenance' => [[array_replace($first, ['description' => 'Unverified claim'])]],
        ];
    }

    private static function program(string $key, int $order): array
    {
        return [
            'key' => $key,
            'order' => $order,
            'names' => ['id' => 'ID', 'en' => 'EN', 'ar' => 'AR'],
            'provenance' => 'test-source',
        ];
    }
}
