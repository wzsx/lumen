<?php
namespace App\Http\Controllers\Api;
use App\Model\UserModel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use function GuzzleHttp\Psr7\str;
class UserController extends Controller{

    public function reg(Request $request){
        $email=$request->input('email');
        $pass=$request->input('pass');
        $pass2=$request->input('pass2');
        $name=$request->input('nick_name');
        $age=$request->input('age');
        $data =[
            'email' =>$email,
            'pass'  =>$pass,
            'name' =>$name,
            'age'   =>$age,
            'pass2' =>$pass2
        ];
        //var_dump($data);die;
//        $url='http://passport.1809.com/reg';
//        ppt.52xiuge.com/reg
        $url='http://ppt.52xiuge.com/reg';
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $url);
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, 0);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //设置post方式提交
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        //执行命令
        $r2 = curl_exec($curl);
        $re2=json_decode($r2,true);
        //var_dump($re2);die;
        return $re2;

    }
    public function apiLogin(Request $request){
        $email=$request->input('email');
        $pass=$request->input('pass');
        $data =[
            'email' =>$email,
            'pass'  =>$pass
        ];
        //var_dump($data);die;
//        $url='http://passport.1809.com/login';
        $url='http://ppt.52xiuge.com/login';
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $url);
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, 0);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //设置post方式提交
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        //执行命令
        $r2 = curl_exec($curl);
        $re2=json_decode($r2,true);
        //var_dump($re2);die;
        return $re2;

    }

}