<?php

namespace App\Http\Middleware;
use Closure;
class LumenUser
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
        if($request->isMethod('OPTIONS')){
            $response = response('');
        }else{
            $response = $next($request);
        }
        return $response;
    }
}
