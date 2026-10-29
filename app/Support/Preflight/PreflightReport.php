<?php

namespace App\Support\Preflight;

use JsonSerializable;

final readonly class PreflightReport implements JsonSerializable
{
    /** @param list<CheckResult> $checks */
    public function __construct(
        public array $checks,
        private SecretRedactor $redactor = new SecretRedactor,
    ) {}

    public function isReady(): bool
    {
        return array_all($this->checks, fn (CheckResult $check): bool => $check->status === CheckStatus::Pass);
    }

    public function jsonSerialize(): array
    {
        return $this->redactor->redact([
            'ready' => $this->isReady(),
            'checks' => array_map(fn (CheckResult $check): array => [
                'id' => $check->id,
                'status' => $check->status->value,
                'summary' => $check->summary,
                'evidence' => $check->evidence,
            ], $this->checks),
        ]);
    }

    public function toHuman(): string
    {
        $lines = ['Hope Charity preflight: '.($this->isReady() ? 'READY' : 'NOT READY')];

        foreach ($this->jsonSerialize()['checks'] as $check) {
            $lines[] = sprintf('[%s] %s — %s', strtoupper($check['status']), $check['id'], $check['summary']);
        }

        return implode(PHP_EOL, $lines);
    }
}
