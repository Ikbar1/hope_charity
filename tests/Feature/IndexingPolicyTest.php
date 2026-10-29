<?php

namespace Tests\Feature;

use Tests\TestCase;

final class IndexingPolicyTest extends TestCase
{
    public function test_testing_environment_is_noindex_and_blocks_robots(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertHeader('X-Robots-Tag', 'noindex, nofollow');

        $this->get('/robots.txt')
            ->assertOk()
            ->assertHeader('X-Robots-Tag', 'noindex, nofollow')
            ->assertSee('Disallow: /', escape: false);

        $this->get('/sitemap.xml')->assertNotFound();
    }

    public function test_production_without_the_explicit_flag_stays_noindex(): void
    {
        config(['app.env' => 'production', 'indexing.enabled' => false]);

        $this->get('/')
            ->assertOk()
            ->assertHeader('X-Robots-Tag', 'noindex, nofollow');

        $this->get('/robots.txt')->assertSee('Disallow: /', escape: false);
    }

    public function test_only_production_with_the_explicit_flag_can_allow_indexing(): void
    {
        config(['app.env' => 'production', 'indexing.enabled' => true]);

        $this->get('/')
            ->assertOk()
            ->assertHeaderMissing('X-Robots-Tag');

        $this->get('/robots.txt')
            ->assertOk()
            ->assertSee('Allow: /', escape: false);
    }
}
