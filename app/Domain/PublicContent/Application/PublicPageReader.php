<?php

namespace App\Domain\PublicContent\Application;

use App\Domain\PublicContent\Contracts\PublicContentSource;
use App\Domain\PublicContent\ReadModels\ContentAvailability;
use App\Domain\PublicContent\ReadModels\PublicPageData;

final readonly class PublicPageReader
{
    public function __construct(private PublicContentSource $source) {}

    public function read(string $pageKey, string $locale): PublicPageData
    {
        return new PublicPageData(
            organizationName: $this->source->organizationName(),
            page: $this->source->institutionalPage($pageKey, $locale),
            localeStatus: $this->source->localeStatus($pageKey, $locale),
            programCategories: $this->source->programCategories($locale),
            programPosts: $this->source->programPosts($locale),
        );
    }

    /** @return array<string, ContentAvailability> */
    public function localeAvailability(string $pageKey): array
    {
        $availability = [];

        foreach (['id', 'en', 'ar'] as $locale) {
            $availability[$locale] = $this->source->localeStatus($pageKey, $locale)->availability;
        }

        return $availability;
    }
}
