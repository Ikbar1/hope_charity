<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

final class PublicRouteMatrixTest extends TestCase
{
    #[DataProvider('publicRouteProvider')]
    public function test_each_public_route_resolves_with_its_explicit_locale(string $uri, string $locale): void
    {
        $this->get($uri)
            ->assertOk()
            ->assertHeader('Content-Language', $locale);
    }

    #[DataProvider('unknownRouteProvider')]
    public function test_unknown_locale_or_route_is_not_found(string $uri): void
    {
        $this->get($uri)->assertNotFound();
    }

    public static function publicRouteProvider(): array
    {
        $routes = [];

        foreach (['id' => '', 'en' => '/en', 'ar' => '/ar'] as $locale => $prefix) {
            foreach (['/' => 'home', '/profil' => 'profile', '/program' => 'program', '/kontak' => 'contact'] as $path => $name) {
                $routes["{$locale} {$name}"] = [$prefix.($path === '/' ? '' : $path) ?: '/', $locale];
            }
        }

        return $routes;
    }

    public static function unknownRouteProvider(): array
    {
        return [
            'unknown locale' => ['/fr'],
            'translated English slug is not implicit' => ['/en/profile'],
            'translated Arabic slug is not implicit' => ['/ar/برنامج'],
            'extra path' => ['/program/example-post'],
        ];
    }
}
