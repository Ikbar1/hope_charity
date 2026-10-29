<?php

namespace Tests\Feature;

use App\Domain\PublicContent\Infrastructure\ConceptContent;
use App\Domain\PublicContent\Infrastructure\StaticPublicContentSource;
use DomainException;
use Tests\TestCase;

final class ConceptPreviewTest extends TestCase
{
    public function test_concept_has_complete_locales_and_explicit_provenance(): void
    {
        foreach (['id', 'en', 'ar'] as $locale) {
            $page = (new StaticPublicContentSource)->institutionalPage('home', $locale);
            self::assertSame('concept', $page->editorialStatus);
            self::assertSame(ConceptContent::SOURCE, $page->provenance);
            self::assertCount(8, $page->blocks['descriptions']);
            self::assertNotEmpty($page->blocks['ribbon']);
        }
    }

    public function test_production_does_not_render_concept_even_with_indexing_enabled(): void
    {
        $this->app->instance('env', 'production');
        config(['indexing.enabled' => true]);
        $this->get('/')->assertOk()->assertDontSee('120+')->assertDontSee('demo-ribbon')->assertSee('Konten terverifikasi');
        self::assertSame([], ConceptContent::read('id'));
    }

    public function test_missing_translation_is_rejected(): void
    {
        $this->expectException(DomainException::class);
        ConceptContent::validate([], 'concept', ConceptContent::SOURCE);
    }

    public function test_unproven_concept_cannot_be_claimed_as_verified(): void
    {
        $this->expectException(DomainException::class);
        ConceptContent::validate(ConceptContent::read('id'), 'verified', '');
    }

    public function test_contact_and_donation_actions_are_absent_in_all_locales(): void
    {
        foreach (['', '/en', '/ar'] as $prefix) {
            $this->get($prefix.'/kontak')->assertOk()->assertSee('<fieldset disabled', false)->assertDontSee('<form', false)->assertDontSee('mailto:')->assertDontSee('tel:');
            $this->get($prefix ?: '/')->assertOk()->assertDontSee('donateDialog')->assertDontSee('#donasi')->assertDontSee('openDonate');
        }
        $this->post('/kontak')->assertStatus(405);
    }
}
