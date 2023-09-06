<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Response;

class RestrictPortal
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Str::contains(URL::full(), 'https://merisehat.pk/portal/')) {
            return abort(response()->json(['message' => 'Unauthorized'], 403));
        } elseif (Str::contains(URL::full(), 'http://merisehat.pk/portal/')) {
            return abort(response()->json(['message' => 'Unauthorized'], 403));
        } elseif (Str::contains(URL::full(), 'https://www.merisehat.pk/portal/')) {
            return abort(response()->json(['message' => 'Unauthorized'], 403));
        } elseif (Str::contains(URL::full(), 'http://www.merisehat.pk/portal/')) {
            return abort(response()->json(['message' => 'Unauthorized'], 403));
        } else {
            return $next($request);
        }
    }
}
