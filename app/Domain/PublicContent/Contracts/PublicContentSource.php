<?php

namespace App\Domain\PublicContent\Contracts;

use App\Domain\PublicContent\ReadModels\InstitutionalPage;
use App\Domain\PublicContent\ReadModels\LocaleContentStatus;
use App\Domain\PublicContent\ReadModels\ProgramCategory;
use App\Domain\PublicContent\ReadModels\ProgramPost;

interface PublicContentSource
{
    public function organizationName(): string;

    public function institutionalPage(string $pageKey, string $locale): InstitutionalPage;

    public function localeStatus(string $pageKey, string $locale): LocaleContentStatus;

    /** @return list<ProgramCategory> */
    public function programCategories(string $locale): array;

    /** @return list<ProgramPost> */
    public function programPosts(string $locale): array;
}
