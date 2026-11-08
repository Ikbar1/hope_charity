<?php

namespace Tests\Feature;

use App\Domain\PublicContent\Infrastructure\FoundationProfile;
use App\Domain\PublicContent\Infrastructure\StaticPublicContentSource;
use Tests\TestCase;

final class FoundationProfileTest extends TestCase
{
    public function test_pdf_profile_is_localized_and_placeholders_keep_their_source(): void
    {
        foreach (['id', 'en', 'ar'] as $locale) {
            $page = (new StaticPublicContentSource)->institutionalPage('profile', $locale);
            self::assertCount(5, $page->blocks['missions']);
            self::assertCount(5, $page->blocks['values']);
            self::assertCount(5, $page->blocks['focusAreas']);
            self::assertCount(4, $page->blocks['partnerships']);
            self::assertSame(FoundationProfile::SOURCE, $page->blockSources['vision']['source']);
            self::assertSame('concept', $page->blockSources['hadith']['status']);
            self::assertSame('concept', $page->blockSources['stats']['status']);
            self::assertSame('concept', $page->blockSources['descriptions']['status']);
            self::assertSame('source-adapted', $page->blockSources['descriptions.0']['status']);
            self::assertArrayNotHasKey('descriptions.5', $page->blockSources);
            self::assertArrayNotHasKey('legalValues', $page->blocks);
            self::assertArrayNotHasKey('boards', $page->blocks);
        }
    }

    public function test_public_contact_details_are_visible_without_internal_registration_or_governance_data(): void
    {
        foreach (['', '/en', '/ar'] as $prefix) {
            $this->get($prefix.'/profil')->assertOk()->assertDontSee('3009260000164')->assertDontSee('Muhammad Hilal, S.Ag.')->assertDontSee('Mohammed Ahmed H Alrabghi')->assertDontSee('Muhammad Firdaus, Lc.')->assertDontSee('Faiz')->assertDontSee('Muhammad Khalid')->assertDontSee('Anas Yusni Syahran')->assertDontSee('Zakariya M. Zarqani')->assertDontSee('[Siap diisi');
            $this->get($prefix.'/kontak')->assertOk()->assertSee('ysnhopecharity@gmail.com')->assertSee('+62 851-5166-3016')->assertSee('Jl. Gintung No. 6')->assertSee('<fieldset disabled', false)->assertDontSee('<form', false);
            $this->get($prefix ?: '/')->assertOk()->assertSee('120+')->assertSee('50.000+')->assertSee('15+')->assertSee('hero-quote')->assertDontSee('openDonate');
        }
    }
}
