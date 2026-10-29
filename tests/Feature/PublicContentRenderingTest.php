<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

final class PublicContentRenderingTest extends TestCase
{
    private const PROGRAMS = [
        'id' => ['Pembangunan Masjid', 'Pembangunan Fasilitas', 'Pembuatan Sumur', 'Santunan Anak Yatim', 'Program Bantuan', 'Program Buka Puasa', 'Bantuan Sosial', 'Bantuan Kemanusiaan'],
        'en' => ['Mosque Construction', 'Facility Development', 'Water Well Construction', 'Orphan Sponsorship', 'Assistance Programs', 'Iftar Programs', 'Social Assistance', 'Humanitarian Aid'],
        'ar' => ['بناء المساجد', 'إنشاء المرافق', 'حفر الآبار', 'كفالة الأيتام', 'برامج المساعدات', 'إفطار الصائمين', 'المساعدات الاجتماعية', 'المساعدات الإنسانية'],
    ];

    #[DataProvider('allPagesProvider')]
    public function test_every_page_reads_the_official_name_from_the_content_contract(string $uri): void
    {
        $this->get($uri)
            ->assertOk()
            ->assertSee('Yayasan Hope Charity Indonesia');
    }

    #[DataProvider('programPageProvider')]
    public function test_program_page_renders_exactly_eight_verified_names_in_order(string $uri, string $locale, string $direction, string $emptyCopy): void
    {
        $response = $this->get($uri)->assertOk();
        $html = $response->getContent();

        $response->assertSeeInOrder(self::PROGRAMS[$locale]);
        self::assertSame(8, substr_count($html, 'class="program-list__item"'));
        self::assertStringContainsString("dir=\"{$direction}\"", $html);
        self::assertStringNotContainsString('<img', $html);
        self::assertStringNotContainsString('mockups/', $html);
        self::assertStringContainsString($emptyCopy, $html);
    }

    public static function allPagesProvider(): array
    {
        $cases = [];

        foreach (['', '/en', '/ar'] as $prefix) {
            foreach (['', '/profil', '/program', '/kontak'] as $path) {
                $uri = $prefix.$path ?: '/';
                $cases[$uri] = [$uri];
            }
        }

        return $cases;
    }

    public static function programPageProvider(): array
    {
        return [
            'Indonesia LTR' => ['/program', 'id', 'ltr', 'Belum ada posting program yang diterbitkan.'],
            'English LTR' => ['/en/program', 'en', 'ltr', 'No program posts have been published yet.'],
            'Arabic RTL' => ['/ar/program', 'ar', 'rtl', 'لم تُنشر أي منشورات للبرامج بعد.'],
        ];
    }
}
