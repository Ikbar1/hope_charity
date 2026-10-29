<?php

namespace App\Console\Commands;

use App\Support\Preflight\PreflightRunner;
use Illuminate\Console\Command;

final class PreflightCommand extends Command
{
    protected $signature = 'hope:preflight {--format=human : Output format: human or json} {--run-checks : Run Composer, tests, and Vite manifest checks}';

    protected $description = 'Verify the Hope Charity runtime and local application gates.';

    public function handle(): int
    {
        $report = (new PreflightRunner(base_path()))->run((bool) $this->option('run-checks'));
        $format = (string) $this->option('format');

        if (! in_array($format, ['human', 'json'], true)) {
            $this->error('Format harus human atau json.');

            return self::INVALID;
        }

        $this->line($format === 'json'
            ? json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR)
            : $report->toHuman());

        return $report->isReady() ? self::SUCCESS : self::FAILURE;
    }
}
