<?php   namespace App\Http\Middleware;

use Closure;

class ThrottleRequestsWithIpWhiteList extends \Illuminate\Routing\Middleware\ThrottleRequests
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next, $maxAttempts = 60, $decayMinutes = 1, $prefix = '')
    {
        $whiteListedIps = explode(',', str_replace(' ', '', env('White_List_Ip')));
        if(in_array($request->ip(), $whiteListedIps)){
            return $next($request);
        }
        return parent::handle($request, $next, $maxAttempts, $decayMinutes, $prefix);
    }
}
