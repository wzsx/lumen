<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Redis;
use Closure;

class LumenMiddleware
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
        $request_uri =$_SERVER ['REQUEST_URI'];
        //echo $request_uri;echo '</br>';
        //echo md5($request_uri);echo '<br>';
        $uri_hash =substr(md5($request_uri),0,10);
        //echo $uri_hash;echo '</br>';
        $ip =$_SERVER['REMOTE_ADDR'];
        $redis_key = 'str:'. $uri_hash .':'.$ip;
        //echo $redis_key;
        $num =Redis::incr($redis_key);
        //echo $num;exit;
        Redis::expire($redis_key,60);
        //echo 'count:'.$num;echo '</br>';
        if($num>5){
            $response =[
                'errno'=>40003,
                'msg'=>'Invalid Request!!!'
            ];
            Redis::expire($redis_key,10);
            return json_encode($response);
        }

        return $next($request);
    }
}

