<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class EnforceNoIndex
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($this->mustBlockIndexing()) {
            $response->headers->set('X-Robots-Tag', 'noindex, nofollow');
        }

        return $response;
    }

    private function mustBlockIndexing(): bool
    {
        return config('app.env') !== 'production' || config('indexing.enabled') !== true;
    }
}
