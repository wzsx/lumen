<?php

namespace App\Http\Middleware;
use App\Model\UserModel;
use Closure;
use Illuminate\Support\Facades\Redis;
class CheckLogin
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
        //验证用户token是否存在
        $user_id=$_POST['uid'];
        $token=$_POST['token'];
        if(empty($token)||empty($user_id)){
            $response=[
                'errno'=>40002,
                'msg'=>'参数不完整'
            ];
            die(json_encode($response,JSON_UNESCAPED_UNICODE));
        }
        //验证token是否有效
//        $ktoken='token:u:'.$user_id;
        $key='token:u:'.$user_id;;
        $local_token =Redis::hget($key,'app:token');
        if($local_token){
            //TODO
            if($token==$local_token){  //token有效
                //TODO 记录日志
                $user_info=UserModel::where(['uid'=>$user_id])->first();
                $response=[
                    'errcode'=>0,
                    'msg'=>'ok',
                    'name'=>$user_info['name'],
                ];
                return (json_encode($response,JSON_UNESCAPED_UNICODE));
                //echo(json_encode($response,JSON_UNESCAPED_UNICODE));
            }else{     //token无效
                $response=[
                    'errno'=>40004,
                    'msg'=>'无效的token'
                ];
                die(json_encode($response,JSON_UNESCAPED_UNICODE));
            }
        }else{
            //TODO 需授权
            $response=[
                'errno'=>40005,
                'msg'=>'请先登录'
            ];
            die(json_encode($response,JSON_UNESCAPED_UNICODE));
        }


        return $next($request);
    }
}
