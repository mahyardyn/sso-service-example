<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class jsonLocalization
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $locale = $request->segment(2);

        if (!in_array($locale, config('app.all_locales')))
            abort(404);

        config(['app.json_locale' => $locale]);

        // Post-Middleware Action
        return $next($request);
    }
}
