<?php

namespace App\Domain\PublicContent\Infrastructure;

use App\Domain\PublicContent\Contracts\PublicContentSource;
use App\Domain\PublicContent\ReadModels\ContentAvailability;
use App\Domain\PublicContent\ReadModels\InstitutionalPage;
use App\Domain\PublicContent\ReadModels\LocaleContentStatus;
use App\Domain\PublicContent\ReadModels\ProgramCategory;
use InvalidArgumentException;

final class StaticPublicContentSource implements PublicContentSource
{
    public const ORGANIZATION_NAME = 'Yayasan Hope Charity Indonesia';

    public const PROVENANCE = 'user-verified-program-list-2026-10-29';

    private const PROGRAMS = [
        ['key' => 'mosque-construction', 'order' => 1, 'names' => ['id' => 'Pembangunan Masjid', 'ar' => 'بناء المساجد', 'en' => 'Mosque Construction'], 'provenance' => self::PROVENANCE],
        ['key' => 'facility-development', 'order' => 2, 'names' => ['id' => 'Pembangunan Fasilitas', 'ar' => 'إنشاء المرافق', 'en' => 'Facility Development'], 'provenance' => self::PROVENANCE],
        ['key' => 'water-well-construction', 'order' => 3, 'names' => ['id' => 'Pembuatan Sumur', 'ar' => 'حفر الآبار', 'en' => 'Water Well Construction'], 'provenance' => self::PROVENANCE],
        ['key' => 'orphan-sponsorship', 'order' => 4, 'names' => ['id' => 'Santunan Anak Yatim', 'ar' => 'كفالة الأيتام', 'en' => 'Orphan Sponsorship'], 'provenance' => self::PROVENANCE],
        ['key' => 'assistance-programs', 'order' => 5, 'names' => ['id' => 'Program Bantuan', 'ar' => 'برامج المساعدات', 'en' => 'Assistance Programs'], 'provenance' => self::PROVENANCE],
        ['key' => 'iftar-programs', 'order' => 6, 'names' => ['id' => 'Program Buka Puasa', 'ar' => 'إفطار الصائمين', 'en' => 'Iftar Programs'], 'provenance' => self::PROVENANCE],
        ['key' => 'social-assistance', 'order' => 7, 'names' => ['id' => 'Bantuan Sosial', 'ar' => 'المساعدات الاجتماعية', 'en' => 'Social Assistance'], 'provenance' => self::PROVENANCE],
        ['key' => 'humanitarian-aid', 'order' => 8, 'names' => ['id' => 'Bantuan Kemanusiaan', 'ar' => 'المساعدات الإنسانية', 'en' => 'Humanitarian Aid'], 'provenance' => self::PROVENANCE],
    ];

    public function __construct()
    {
        (new StaticContentValidator)->validatePrograms(self::PROGRAMS);
    }

    public function organizationName(): string
    {
        return self::ORGANIZATION_NAME;
    }

    public function institutionalPage(string $pageKey, string $locale): InstitutionalPage
    {
        $this->assertSupportedLocale($locale);
        $availability = $pageKey === 'program'
            ? ContentAvailability::Available
            : ContentAvailability::Unavailable;

        return new InstitutionalPage(
            key: $pageKey,
            locale: $locale,
            availability: $availability,
            title: null,
            body: null,
            provenance: $availability === ContentAvailability::Available ? self::PROVENANCE : null,
        );
    }

    public function localeStatus(string $pageKey, string $locale): LocaleContentStatus
    {
        $this->assertSupportedLocale($locale);

        return new LocaleContentStatus(
            pageKey: $pageKey,
            locale: $locale,
            availability: $pageKey === 'program' ? ContentAvailability::Available : ContentAvailability::Unavailable,
        );
    }

    public function programCategories(string $locale): array
    {
        $this->assertSupportedLocale($locale);

        return array_map(
            fn (array $program): ProgramCategory => new ProgramCategory(
                key: $program['key'],
                order: $program['order'],
                locale: $locale,
                name: $program['names'][$locale],
                mediaUrl: null,
                provenance: $program['provenance'],
            ),
            self::PROGRAMS,
        );
    }

    public function programPosts(string $locale): array
    {
        $this->assertSupportedLocale($locale);

        return [];
    }

    private function assertSupportedLocale(string $locale): void
    {
        if (! in_array($locale, ['id', 'en', 'ar'], true)) {
            throw new InvalidArgumentException('Unsupported locale.');
        }
    }
}
