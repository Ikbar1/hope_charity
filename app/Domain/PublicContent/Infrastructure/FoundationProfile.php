<?php

namespace App\Domain\PublicContent\Infrastructure;

use DomainException;

final class FoundationProfile
{
    public const SOURCE = 'user-supplied-foundation-profile-2026-11-06; sha256:d776782471865b652f33e8fc4287f4e9444897c5166f82730fc3d88a202d766d';

    public static function read(string $locale): array
    {
        $path = resource_path('content/profile/'.$locale.'.json');
        if (! is_file($path)) {
            return [];
        }
        $content = json_decode(file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);
        foreach (['hero', 'profile', 'story', 'vision', 'missions', 'values', 'contactDetails', 'focusAreas', 'partnerships'] as $key) {
            if (empty($content[$key]) || ! is_array($content[$key])) {
                throw new DomainException('Incomplete foundation profile: '.$key);
            }
        }

        return $content;
    }
}
