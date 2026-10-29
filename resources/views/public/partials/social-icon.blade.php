<svg viewBox="0 0 24 24" width="22" height="22" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
@switch($platform)
    @case('instagram')
        <rect x="3" y="3" width="18" height="18" rx="5" stroke="currentColor" stroke-width="1.8"/>
        <circle cx="12" cy="12" r="4" stroke="currentColor" stroke-width="1.8"/>
        <circle cx="17.5" cy="6.5" r="1" fill="currentColor"/>
        @break
    @case('facebook')
        <path d="M14 21v-8h3l.5-3H14V8.2c0-.9.3-1.7 1.8-1.7H18V3.2c-.4-.1-1.8-.2-3-.2-3 0-5 1.8-5 5v2H7v3h3v8h4Z" fill="currentColor"/>
        @break
    @case('youtube')
        <rect x="2.5" y="5" width="19" height="14" rx="4" stroke="currentColor" stroke-width="1.8"/>
        <path d="m10 8.5 6 3.5-6 3.5v-7Z" fill="currentColor"/>
        @break
    @case('tiktok')
        <path d="M14 3h3c.3 2.4 1.7 3.8 4 4v3a8 8 0 0 1-4-1.3V16a6 6 0 1 1-6-6v3a3 3 0 1 0 3 3V3Z" fill="currentColor"/>
        @break
@endswitch
</svg>
