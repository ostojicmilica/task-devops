<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BasicAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        if($request->getUser() != env('AUTH_USERNAME') || $request->getPassword() != env('AUTH_PASSWORD')) {
            $headers = array('WWW-Authenticate' => 'Basic');
            return response('you are not authorized to access this resource/api', 401, $headers);
        }
        return $next($request);
    }
}