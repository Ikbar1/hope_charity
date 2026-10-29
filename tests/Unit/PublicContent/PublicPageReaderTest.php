<?php

namespace Tests\Unit\PublicContent;

use App\Domain\PublicContent\Application\PublicPageReader;
use App\Domain\PublicContent\Contracts\PublicContentSource;
use App\Domain\PublicContent\ReadModels\ContentAvailability;
use App\Domain\PublicContent\ReadModels\InstitutionalPage;
use App\Domain\PublicContent\ReadModels\LocaleContentStatus;
use App\Domain\PublicContent\ReadModels\ProgramCategory;
use PHPUnit\Framework\TestCase;

final class PublicPageReaderTest extends TestCase
{
    public function test_renderer_read_model_depends_on_the_source_contract_instead_of_storage_shape(): void
    {
        $fake = new class implements PublicContentSource
        {
            public array $calls = [];

            public function organizationName(): string
            {
                $this->calls[] = __FUNCTION__;

                return 'Verified Foundation';
            }

            public function institutionalPage(string $pageKey, string $locale): InstitutionalPage
            {
                $this->calls[] = __FUNCTION__;

                return new InstitutionalPage($pageKey, $locale, ContentAvailability::Available, 'Fake title', null, 'test');
            }

            public function localeStatus(string $pageKey, string $locale): LocaleContentStatus
            {
                $this->calls[] = __FUNCTION__;

                return new LocaleContentStatus($pageKey, $locale, ContentAvailability::Available);
            }

            public function programCategories(string $locale): array
            {
                $this->calls[] = __FUNCTION__;

                return [new ProgramCategory('fake', 1, $locale, 'Fake category', null, 'test')];
            }

            public function programPosts(string $locale): array
            {
                $this->calls[] = __FUNCTION__;

                return [];
            }
        };

        $data = (new PublicPageReader($fake))->read('profile', 'en');

        self::assertSame('Verified Foundation', $data->organizationName);
        self::assertSame('Fake title', $data->page->title);
        self::assertSame('Fake category', $data->programCategories[0]->name);
        self::assertSame([], $data->programPosts);
        self::assertSame([
            'organizationName',
            'institutionalPage',
            'localeStatus',
            'programCategories',
            'programPosts',
        ], $fake->calls);
    }
}
