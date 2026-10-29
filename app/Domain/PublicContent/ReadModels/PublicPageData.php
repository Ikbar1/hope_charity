<?php

namespace App\Domain\PublicContent\ReadModels;

final readonly class PublicPageData
{
    /**
     * @param  list<ProgramCategory>  $programCategories
     * @param  list<ProgramPost>  $programPosts
     */
    public function __construct(
        public string $organizationName,
        public InstitutionalPage $page,
        public LocaleContentStatus $localeStatus,
        public array $programCategories,
        public array $programPosts,
    ) {}
}
