<?php

namespace Tests\Unit\PublicContent;

use App\Domain\PublicContent\Infrastructure\StaticPublicContentSource;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class StaticPublicContentSourceTest extends TestCase
{
    private const EXPECTED = [
        ['mosque-construction', 1, 'Pembangunan Masjid', 'بناء المساجد', 'Mosque Construction'],
        ['facility-development', 2, 'Pembangunan Fasilitas', 'إنشاء المرافق', 'Facility Development'],
        ['water-well-construction', 3, 'Pembuatan Sumur', 'حفر الآبار', 'Water Well Construction'],
        ['orphan-sponsorship', 4, 'Santunan Anak Yatim', 'كفالة الأيتام', 'Orphan Sponsorship'],
        ['assistance-programs', 5, 'Program Bantuan', 'برامج المساعدات', 'Assistance Programs'],
        ['iftar-programs', 6, 'Program Buka Puasa', 'إفطار الصائمين', 'Iftar Programs'],
        ['social-assistance', 7, 'Bantuan Sosial', 'المساعدات الاجتماعية', 'Social Assistance'],
        ['humanitarian-aid', 8, 'Bantuan Kemanusiaan', 'المساعدات الإنسانية', 'Humanitarian Aid'],
    ];

    public function test_it_exposes_only_the_verified_official_name(): void
    {
        self::assertSame('Yayasan Hope Charity Indonesia', (new StaticPublicContentSource)->organizationName());
    }

    #[DataProvider('localeProvider')]
    public function test_the_eight_programs_match_the_verified_mapping_exactly(string $locale, int $nameIndex): void
    {
        $programs = (new StaticPublicContentSource)->programCategories($locale);
        $actual = array_map(
            fn ($program): array => [$program->key, $program->order, $program->name],
            $programs,
        );
        $expected = array_map(
            fn (array $program): array => [$program[0], $program[1], $program[$nameIndex]],
            self::EXPECTED,
        );

        self::assertCount(8, $actual);
        self::assertSame($expected, $actual);
    }

    public static function localeProvider(): array
    {
        return [
            'Indonesia' => ['id', 2],
            'Arabic' => ['ar', 3],
            'English' => ['en', 4],
        ];
    }
}
