<?php

namespace App\Domain\PublicContent\Infrastructure;

use DomainException;

final class StaticContentValidator
{
    private const REQUIRED_LOCALES = ['id', 'en', 'ar'];

    private const FACTUAL_FIELDS = ['description', 'media_url', 'location', 'status', 'achievement', 'donation'];

    /** @param list<array<string, mixed>> $programs */
    public function validatePrograms(array $programs): void
    {
        $keys = [];
        $orders = [];

        foreach ($programs as $index => $program) {
            $key = trim((string) ($program['key'] ?? ''));
            $order = $program['order'] ?? null;

            if ($key === '' || isset($keys[$key])) {
                throw new DomainException("Program index {$index} has an empty or duplicate key.");
            }

            if (! is_int($order) || $order < 1 || isset($orders[$order])) {
                throw new DomainException("Program {$key} has an invalid or duplicate order.");
            }

            $keys[$key] = true;
            $orders[$order] = true;

            foreach (self::REQUIRED_LOCALES as $locale) {
                if (trim((string) ($program['names'][$locale] ?? '')) === '') {
                    throw new DomainException("Program {$key} is missing required locale {$locale}.");
                }
            }

            if (trim((string) ($program['provenance'] ?? '')) === '') {
                throw new DomainException("Program {$key} has no provenance for its verified names.");
            }

            foreach (self::FACTUAL_FIELDS as $field) {
                if (trim((string) ($program[$field] ?? '')) === '') {
                    continue;
                }

                if (trim((string) ($program['field_provenance'][$field] ?? '')) === '') {
                    throw new DomainException("Program {$key} has factual field {$field} without provenance.");
                }
            }
        }
    }
}
