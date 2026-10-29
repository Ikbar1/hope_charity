<?php

namespace App\Domain\PublicContent\ReadModels;

final readonly class ProgramCategory
{
    public function __construct(
        public string $key,
        public int $order,
        public string $locale,
        public string $name,
        public ?string $mediaUrl,
        public string $provenance,
    ) {}
}
