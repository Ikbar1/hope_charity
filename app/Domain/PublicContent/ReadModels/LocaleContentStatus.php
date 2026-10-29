<?php

namespace App\Domain\PublicContent\ReadModels;

final readonly class LocaleContentStatus
{
    public function __construct(
        public string $pageKey,
        public string $locale,
        public ContentAvailability $availability,
    ) {}
}
