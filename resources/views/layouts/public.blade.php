<!doctype html>
<html lang="{{ $locale }}" dir="{{ $locale === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('public.pages.'.$pageKey) }} — {{ $pageData->organizationName }}</title>
    <link rel="canonical" href="{{ route('public.'.$locale.'.'.$pageKey) }}">
    @foreach ($localeAvailability as $alternateLocale => $availability)
        @if ($availability === \App\Domain\PublicContent\ReadModels\ContentAvailability::Available)
            <link rel="alternate" hreflang="{{ $alternateLocale }}" href="{{ route('public.'.$alternateLocale.'.'.$pageKey) }}">
        @endif
    @endforeach
    @if (($localeAvailability['id'] ?? null) === \App\Domain\PublicContent\ReadModels\ContentAvailability::Available)
        <link rel="alternate" hreflang="x-default" href="{{ route('public.id.'.$pageKey) }}">
    @endif
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <a class="skip-link" href="#main-content">{{ __('public.skip_to_content') }}</a>
    <header class="site-header">
        <div class="site-header__inner">
            <a class="brand" href="{{ route('public.'.$locale.'.home') }}">{{ $pageData->organizationName }}</a>
            <nav class="primary-nav" aria-label="{{ __('public.primary_navigation') }}">
                <ul>
                    @foreach (['home', 'profile', 'program', 'contact'] as $navKey)
                        <li><a href="{{ route('public.'.$locale.'.'.$navKey) }}" @if ($pageKey === $navKey) aria-current="page" @endif>{{ __('public.pages.'.$navKey) }}</a></li>
                    @endforeach
                </ul>
            </nav>
            <nav class="language-nav" aria-label="{{ __('public.language_navigation') }}">
                <ul>
                    @foreach (['id' => 'Bahasa Indonesia', 'en' => 'English', 'ar' => 'العربية'] as $languageCode => $languageName)
                        <li>
                            <a lang="{{ $languageCode }}" hreflang="{{ $languageCode }}" href="{{ route('public.'.$languageCode.'.'.$pageKey) }}" @if ($locale === $languageCode) aria-current="page" @endif>{{ $languageName }}</a>
                        </li>
                    @endforeach
                </ul>
            </nav>
        </div>
    </header>
    <main class="page-shell" id="main-content" tabindex="-1">
        <div class="page-shell__inner">@yield('content')</div>
    </main>
    <footer class="site-footer"><div class="site-footer__inner">{{ $pageData->organizationName }}</div></footer>
</body>
</html>
