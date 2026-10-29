<?php

namespace App\Http\Controllers;

use App\Domain\PublicContent\Application\PublicPageReader;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

final class PublicPageController extends Controller
{
    public function show(Request $request, PublicPageReader $reader): View
    {
        $locale = app()->getLocale();
        $pageKey = (string) $request->route('pageKey');

        return view('public.page', [
            'locale' => $locale,
            'pageKey' => $pageKey,
            'pageData' => $reader->read($pageKey, $locale),
            'localeAvailability' => $reader->localeAvailability($pageKey),
        ]);
    }
}
