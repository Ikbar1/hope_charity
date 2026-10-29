<?php

namespace App\Domain\PublicContent\ReadModels;

final readonly class InstitutionalPage
{
    public function __construct(
        public string $key,
        public string $locale,
        public ContentAvailability $availability,
        public ?string $title,
        public ?string $body,
        public ?string $provenance,
    ) {}
}
