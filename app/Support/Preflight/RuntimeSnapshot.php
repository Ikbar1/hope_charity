<?php

namespace App\Support\Preflight;

final readonly class RuntimeSnapshot
{
    /**
     * @param  list<string>  $loadedExtensions
     * @param  array<string, mixed>|null  $provenance
     */
    public function __construct(
        public string $phpVersion,
        public string $osFamily,
        public string $sapi,
        public array $loadedExtensions,
        public string|false $sessionUploadProgress,
        public ?array $provenance,
    ) {}
}
