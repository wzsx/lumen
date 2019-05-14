<?php
namespace App\Http\Controllers\Openssl;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redis;
class OpensslController extends Controller
{

    public function openssl()
    {
        //加密
        $str = 'this is a 加密文件';

        $base = base64_encode($str);
        echo $base;
        $token=$base;
        //$token = $this->generateLoginToken($base);
        $redis_token_key = 'openssl';
        Redis::set($redis_token_key, $token);
        Redis::expire($redis_token_key, 604800);

    }

    protected function generateLoginToken($uid)
    {
        return substr(sha1($uid . time() . Str::random(10)), 5, 15);

    }
    public function ksjm(){
    $str='cdaaABCDEFGHIJKLMNOPQRSTUVWXYZ';
    echo base64_decode();
    }

}