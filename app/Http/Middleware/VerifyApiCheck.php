<?php

namespace App\Http\Middleware;

use App\Models\ApiLog;
use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
class VerifyApiCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    { 
        $token = $request->bearerToken();
        // Get the user associated with the token
        $user = User::where('api_token', $token)->first();
        if ($user==null||!$user->api_enabled){
            return response('Unauthorized', 401);
        }
        if ($user) {
            Auth::login($user);
        }
        return $next($request);
    }
}
