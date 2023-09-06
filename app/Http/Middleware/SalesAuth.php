<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\ApiToken;
use Response;

class SalesAuth
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
        $row = '';
        if($request->bearerToken() != '' && $request->header('device-id') != ''){
            $row = ApiToken::where('token', $request->bearerToken())->where('device_id', $request->header('device-id'))->first();
            if ($row) {
                if (date('Ymd') >= date('Ymd', strtotime($row->expiry_date))) {
                    return response()->json(['code' => 401, 'status' => false, 'message' => 'Access Token has been expired.', 'data' => null], 401);
                }
            }
        }
        if( !$row ){
            return response()->json(['code'=>401,'status'=> false, 'message' => 'Unauthorized: Access Token.', 'data' =>null], 401);
        }else {
//            $request->headers->set('user_id', $row->user_id);
            $request->merge([
                'user_id' => $row->id
            ]);
            return $next($request);
        }
    }
}
