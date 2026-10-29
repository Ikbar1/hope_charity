<?php

namespace Tests\Feature;

use Tests\TestCase;

final class LocaleNavigationMetadataTest extends TestCase
{
    public function test_language_links_preserve_the_current_page_without_javascript(): void
    {
        $response = $this->get('/en/profil')->assertOk();

        $response->assertSee('href="'.route('public.id.profile').'"', false);
        $response->assertSee('href="'.route('public.en.profile').'"', false);
        $response->assertSee('href="'.route('public.ar.profile').'"', false);
        $response->assertDontSee('onclick=', false);
    }

    public function test_program_page_emits_canonical_and_available_hreflang_links(): void
    {
        $response = $this->get('/en/program')->assertOk();

        $response->assertSee('<link rel="canonical" href="'.route('public.en.program').'">', false);
        foreach (['id', 'en', 'ar'] as $locale) {
            $response->assertSee('<link rel="alternate" hreflang="'.$locale.'" href="'.route("public.{$locale}.program").'">', false);
        }
        $response->assertSee('hreflang="x-default"', false);
    }

    public function test_concept_profile_has_localized_content_without_indonesian_fallback(): void
    {
        $this->get('/en/profil')
            ->assertOk()
            ->assertSee('Growing Together,')
            ->assertDontSee('Konten terverifikasi untuk halaman ini belum tersedia.')
            ->assertSee('<link rel="alternate"', false);
    }
}
