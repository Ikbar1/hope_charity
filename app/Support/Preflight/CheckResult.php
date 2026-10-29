<?php

namespace App\Support\Preflight;

final readonly class CheckResult
{
    public function __construct(
        public string $id,
        public CheckStatus $status,
        public string $summary,
        public array $evidence = [],
    ) {}
}
