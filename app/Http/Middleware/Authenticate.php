<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            $explode = explode('/', $request->getRequestUri());
            if(isset($explode[2]) && $explode[2] === 'admin'){
                return route('login');
            }else{
                return abort(response()->json(['message' => 'Unauthorized'], 403));
            }
        }
    }
}
