<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class JsonRequestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        // Pre-Middleware Action
        $request->headers->set('Accept', 'application/json');

        if ($request->isJson()) {
            $data = $request->json()->all();
            $request->request->replace(is_array($data) ? $data : []);
        }

        // Post-Middleware Action
        return $next($request);
    }
}
