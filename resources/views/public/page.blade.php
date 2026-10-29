@extends('layouts.public')
@section('content')
@if ($pageData->page->blocks !== [])
@php($copy = $pageData->page->blocks)
@include('public.pages.'.$pageKey)
@else
<section class="fallback-page"><h1>{{ __('public.pages.'.$pageKey) }}</h1><p>{{ __('public.content_unavailable') }}</p>
@if ($pageKey === 'program')<ol>@foreach ($pageData->programCategories as $program)<li>{{ $program->name }}</li>@endforeach</ol>@endif
</section>
@endif
@endsection
