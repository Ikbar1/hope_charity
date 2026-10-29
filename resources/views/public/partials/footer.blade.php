<div class="footer-main">
    <div class="footer-identity">
        <a class="footer-logo" href="{{ route('public.'.$locale.'.home') }}"><img src="{{ asset('images/hope-charity-logo-transparent.png') }}" width="1254" height="1254" loading="lazy" alt="{{ $pageData->organizationName }}"></a>
        <p>{{ $pageData->organizationName }}</p>
    </div>
    <nav class="footer-navigation" aria-labelledby="footer-nav-title">
        <h2 id="footer-nav-title">{{ __('public.footer.explore') }}</h2>
        <ul>
            @foreach (['home', 'profile', 'program', 'contact'] as $key)
                <li><a href="{{ route('public.'.$locale.'.'.$key) }}" @if ($pageKey === $key) aria-current="page" @endif>{{ __('public.pages.'.$key) }}</a></li>
            @endforeach
        </ul>
    </nav>
    <section class="footer-social" aria-labelledby="footer-social-title">
        <h2 id="footer-social-title">{{ __('public.footer.social') }}</h2>
        <ul class="social-icons" aria-describedby="social-status">
            @foreach (['instagram' => 'Instagram', 'facebook' => 'Facebook', 'youtube' => 'YouTube', 'tiktok' => 'TikTok'] as $platform => $name)
                <li><span class="social-icon" role="img" aria-label="{{ $name }}" title="{{ $name }} — {{ __('public.footer.social_pending') }}">@include('public.partials.social-icon', ['platform' => $platform])</span></li>
            @endforeach
        </ul>
        <p id="social-status">{{ __('public.footer.social_pending') }}</p>
    </section>
</div>
<div class="footer-bottom">
    <span>© {{ now()->year }} {{ $pageData->organizationName }}</span>
    <a href="#top">{{ $pageData->page->blocks['back'] ?? __('public.footer.back') }} <span aria-hidden="true">↑</span></a>
</div>
