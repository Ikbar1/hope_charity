<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class SetPublicLocale
{
    public function handle(Request $request, Closure $next, string $locale): Response
    {
        abort_unless(in_array($locale, ['id', 'en', 'ar'], true), 404);

        app()->setLocale($locale);
        $response = $next($request);
        $response->headers->set('Content-Language', $locale);

        return $response;
    }
}
