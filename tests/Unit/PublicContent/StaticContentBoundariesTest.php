<?php

namespace Tests\Unit\PublicContent;

use App\Domain\PublicContent\Infrastructure\StaticPublicContentSource;
use App\Domain\PublicContent\ReadModels\ContentAvailability;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class StaticContentBoundariesTest extends TestCase
{
    public function test_unverified_institutional_pages_are_explicitly_unavailable_in_every_locale(): void
    {
        $source = new StaticPublicContentSource;

        foreach (['id', 'en', 'ar'] as $locale) {
            foreach (['home', 'profile', 'contact'] as $pageKey) {
                $page = $source->institutionalPage($pageKey, $locale);

                self::assertSame(ContentAvailability::Unavailable, $page->availability);
                self::assertNull($page->title);
                self::assertNull($page->body);
                self::assertNull($page->provenance);
            }
        }
    }

    public function test_posts_are_empty_and_program_media_is_absent_instead_of_broken(): void
    {
        $source = new StaticPublicContentSource;

        foreach (['id', 'en', 'ar'] as $locale) {
            self::assertSame([], $source->programPosts($locale));

            foreach ($source->programCategories($locale) as $program) {
                self::assertNull($program->mediaUrl);
            }
        }
    }

    public function test_public_contract_does_not_expose_mockup_claims_or_example_media(): void
    {
        $source = new StaticPublicContentSource;
        $payload = json_encode([
            'name' => $source->organizationName(),
            'pages' => array_map(fn (string $key) => $source->institutionalPage($key, 'id'), ['home', 'profile', 'contact']),
            'programs' => $source->programCategories('id'),
            'posts' => $source->programPosts('id'),
        ], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);

        foreach (['donasi', 'hadis', 'visi', 'misi', 'statistik', 'capaian', 'testimoni', '.jpg', '.png'] as $forbidden) {
            self::assertStringNotContainsStringIgnoringCase($forbidden, $payload);
        }
    }

    public function test_unknown_locale_is_rejected_without_hidden_fallback(): void
    {
        $this->expectException(InvalidArgumentException::class);

        (new StaticPublicContentSource)->programCategories('fr');
    }
}
