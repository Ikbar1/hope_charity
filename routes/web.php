<?php

use App\Http\Controllers\PublicPageController;
use App\Http\Middleware\SetPublicLocale;
use Illuminate\Support\Facades\Route;

$pages = [
    'home' => '/',
    'profile' => '/profil',
    'program' => '/program',
    'contact' => '/kontak',
];

foreach (['id' => '', 'en' => '/en', 'ar' => '/ar'] as $locale => $prefix) {
    Route::prefix($prefix)
        ->middleware(SetPublicLocale::class.':'.$locale)
        ->group(function () use ($locale, $pages): void {
            foreach ($pages as $pageKey => $uri) {
                Route::get($uri, [PublicPageController::class, 'show'])
                    ->name("public.{$locale}.{$pageKey}")
                    ->defaults('pageKey', $pageKey);
            }
        });
}

Route::get('/robots.txt', function () {
    $indexingAllowed = config('app.env') === 'production' && config('indexing.enabled') === true;
    $contents = $indexingAllowed
        ? "User-agent: *\nAllow: /\n"
        : "User-agent: *\nDisallow: /\n";

    return response($contents, 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
});
