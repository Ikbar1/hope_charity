<?php

namespace App\Domain\PublicContent\ReadModels;

final readonly class ProgramPost
{
    public function __construct(
        public string $key,
        public string $categoryKey,
        public string $locale,
        public string $title,
        public ?string $mediaUrl,
        public string $provenance,
    ) {}
}
