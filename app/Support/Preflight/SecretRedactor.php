<?php

namespace App\Support\Preflight;

final class SecretRedactor
{
    private const SENSITIVE_KEY = '/(?:secret|password|passwd|token|credential|authorization|api[_-]?key|private[_-]?key|dsn)/i';

    public function redact(mixed $value, ?string $key = null): mixed
    {
        if ($key !== null && preg_match(self::SENSITIVE_KEY, $key) === 1) {
            return '[REDACTED]';
        }

        if (is_array($value)) {
            $redacted = [];

            foreach ($value as $childKey => $childValue) {
                $redacted[$childKey] = $this->redact($childValue, (string) $childKey);
            }

            return $redacted;
        }

        if (! is_string($value)) {
            return $value;
        }

        $value = preg_replace('#([a-z][a-z0-9+.-]*://)[^/@\s]+:[^/@\s]+@#i', '$1[REDACTED]@', $value) ?? '[REDACTED]';
        $value = preg_replace('#[A-Za-z]:\\\\Users\\\\[^\\\\\s]+#i', '[PRIVATE_PATH]', $value) ?? '[REDACTED]';
        $value = preg_replace('#/(?:home|Users)/[^/\s]+#', '[PRIVATE_PATH]', $value) ?? '[REDACTED]';

        return $value;
    }
}
