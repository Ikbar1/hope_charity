@extends('layouts.public')

@section('content')
    <p class="page-kicker">{{ __('public.foundation_label') }}</p>
    <h1>{{ __('public.pages.'.$pageKey) }}</h1>
    @if ($pageKey === 'program')
        <section class="program-section" aria-labelledby="program-categories-title">
            <h2 id="program-categories-title">{{ __('public.program_categories') }}</h2>
            <ol class="program-list">
                @foreach ($pageData->programCategories as $program)
                    <li class="program-list__item" data-program-key="{{ $program->key }}">
                        <span class="program-list__number" aria-hidden="true">{{ str_pad((string) $program->order, 2, '0', STR_PAD_LEFT) }}</span>
                        <span class="program-list__name">{{ $program->name }}</span>
                    </li>
                @endforeach
            </ol>
        </section>
        <section class="posting-empty" aria-labelledby="program-posts-title">
            <h2 id="program-posts-title">{{ __('public.program_posts') }}</h2>
            <p>{{ __('public.no_program_posts') }}</p>
        </section>
    @elseif ($pageData->localeStatus->availability === \App\Domain\PublicContent\ReadModels\ContentAvailability::Unavailable)
        <div class="empty-state" role="status"><p>{{ __('public.content_unavailable') }}</p></div>
    @endif
@endsection
