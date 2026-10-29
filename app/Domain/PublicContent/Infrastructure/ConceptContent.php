<?php

namespace App\Domain\PublicContent\Infrastructure;

use DomainException;
use Illuminate\Foundation\Application;

final class ConceptContent
{
    public const SOURCE = 'mockups/charity-demo; user-authorized-review-2026-10-29';

    public static function allowed(): bool
    {
        return app() instanceof Application && app()->environment(['local', 'testing', 'preview']);
    }

    public static function read(string $locale): array
    {
        if (! self::allowed()) {
            return [];
        }

        $path = resource_path('content/concept/'.$locale.'.json');
        if (! is_file($path)) {
            return [];
        }

        $content = json_decode(file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);
        self::validate($content, 'concept', self::SOURCE);

        return $content;
    }

    public static function validate(array $content, string $status, string $source): void
    {
        if ($status !== 'concept' || trim($source) === '') {
            throw new DomainException('Concept content requires explicit status and provenance.');
        }
        $reference = json_decode(file_get_contents(resource_path('content/concept/id.json')), true, 512, JSON_THROW_ON_ERROR);
        $walk = function (array $expected, array $actual) use (&$walk): void {
            foreach ($expected as $key => $value) {
                if (! array_key_exists($key, $actual)) {
                    throw new DomainException('Missing concept translation: '.$key);
                }
                if (is_array($value)) {
                    if (! is_array($actual[$key])) {
                        throw new DomainException('Invalid concept translation structure.');
                    }
                    $walk($value, $actual[$key]);
                } elseif (! is_string($actual[$key]) || trim($actual[$key]) === '') {
                    throw new DomainException('Empty concept translation: '.$key);
                }
            }
        };
        $walk($reference, $content);
    }
}
