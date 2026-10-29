<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

final class AccessiblePublicShellTest extends TestCase
{
    #[DataProvider('localePageProvider')]
    public function test_shell_has_locale_direction_landmarks_and_keyboard_navigation(string $uri, string $locale, string $direction): void
    {
        $this->get($uri)
            ->assertOk()
            ->assertSee("<html lang=\"{$locale}\" dir=\"{$direction}\">", false)
            ->assertSee('class="skip-link" href="#main-content"', false)
            ->assertSee('<header class="site-header">', false)
            ->assertSee('<nav id="primary-nav" class="primary-nav"', false)
            ->assertSee('<main id="main-content" tabindex="-1">', false)
            ->assertSee('<footer>', false)
            ->assertSee('aria-current="page"', false);
    }

    public function test_css_preserves_visible_focus_responsive_safety_and_reduced_motion(): void
    {
        $css = file_get_contents(resource_path('css/app.css'));

        self::assertStringContainsString(':focus-visible', $css);
        self::assertStringContainsString('@media(max-width:760px)', $css);
        self::assertStringContainsString('@media(prefers-reduced-motion:reduce)', $css);
        self::assertStringContainsString('min-width:320px', $css);
    }

    public static function localePageProvider(): array
    {
        return [
            'Indonesia' => ['/', 'id', 'ltr'],
            'English' => ['/en/profil', 'en', 'ltr'],
            'Arabic' => ['/ar/program', 'ar', 'rtl'],
        ];
    }
}
