<!doctype html>
<html lang="{{ $locale }}" dir="{{ $locale === 'ar' ? 'rtl' : 'ltr' }}">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>{{ __('public.pages.'.$pageKey) }} — {{ $pageData->organizationName }}</title>
<meta name="description" content="{{ $pageData->page->blocks['concept'] ?? __('public.content_unavailable') }}">
<link rel="canonical" href="{{ route('public.'.$locale.'.'.$pageKey) }}">
@foreach ($localeAvailability as $code => $available)
@if ($available === \App\Domain\PublicContent\ReadModels\ContentAvailability::Available)
<link rel="alternate" hreflang="{{ $code }}" href="{{ route('public.'.$code.'.'.$pageKey) }}">
@endif
@endforeach
@if (($localeAvailability['id'] ?? null) === \App\Domain\PublicContent\ReadModels\ContentAvailability::Available)
<link rel="alternate" hreflang="x-default" href="{{ route('public.id.'.$pageKey) }}">
@endif
@vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body id="top">
<a class="skip-link" href="#main-content">{{ __('public.skip_to_content') }}</a>
@if ($pageData->page->editorialStatus === 'concept')<div class="demo-ribbon">{{ $pageData->page->blocks['ribbon'] }}</div>@endif
<header class="site-header">
<a class="brand" href="{{ route('public.'.$locale.'.home') }}"><img src="{{ asset('images/hope-charity-logo-transparent.png') }}" width="1254" height="1254" alt="{{ $pageData->organizationName }}"></a>
<button class="menu-toggle" hidden aria-label="{{ __('public.primary_navigation') }}" aria-controls="primary-nav" aria-expanded="false">☰</button>
<nav id="primary-nav" class="primary-nav" aria-label="{{ __('public.primary_navigation') }}">
@foreach (['home','profile','program','contact'] as $key)<a href="{{ route('public.'.$locale.'.'.$key) }}" @if ($pageKey === $key) aria-current="page" class="active" @endif>{{ __('public.pages.'.$key) }}</a>@endforeach
</nav>
<nav class="language-nav" aria-label="{{ __('public.language_navigation') }}">
@foreach (['id'=>'ID','en'=>'EN','ar'=>'العربية'] as $code=>$name)<a lang="{{ $code }}" hreflang="{{ $code }}" href="{{ route('public.'.$code.'.'.$pageKey) }}" @if ($locale === $code) aria-current="page" @endif>{{ $name }}</a>@endforeach
</nav></header>
<main id="main-content" tabindex="-1">@yield('content')</main>
<footer>@include('public.partials.footer')</footer>
</body></html>
