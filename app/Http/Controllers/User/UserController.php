<?php
namespace  App\Http\Controllers\User;
use App\Model\ApiModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;

class UserController extends Controller{
    public function UserReg(Request $request){
        $pass1=$request->input('password1');
        $pass2=$request->input('password2');
        $email=$request->input('email');
        if($pass1!=$pass2){
            $response=[
                'errno'=>50002,
                'msg'=>'两次输入的密码不一致'
            ];
            die(json_encode($response,JSON_UNESCAPED_UNICODE));
        }
        $e=ApiModel::where(['email'=>$email])->first();
        if($e){
            $response=[
                'errno'=>50004,
                'msg'=>'Email已存在'
            ];
            die(json_encode($response,JSON_UNESCAPED_UNICODE));
        }
        //密码加密处理
        $pass=password_hash($pass1,PASSWORD_BCRYPT);
        $data=[
            'name'=>$request->input('user_name'),
            'email'=>$email,
            'pass'=>$pass,
        ];
//加密数据
        $json_str=json_encode($data);
        $k=openssl_get_privatekey('file://keys/rsa_private_key.pem');
        //$k=openssl_get_privatekey('file://'.storage_path('app/keys/rsa_private_key.pem'));
//          echo $k;die;
        openssl_private_encrypt($json_str,$enc_data,$k);
        var_dump($enc_data);

        $api_url='http://client.1809a.com/useren';
        $ch=curl_init();
        curl_setopt($ch,CURLOPT_URL,$api_url);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$enc_data);
        curl_setopt($ch,CURLOPT_HTTPHEADER,[
            'Content-type:text/plain'
        ]);
        $response=curl_exec($ch);
        //监控错误码
        $err_code=curl_errno($ch);
        //var_dump($err_code);
        if($err_code>0){
            echo "CURL 错误码：".$err_code;exit;
        }
        curl_close($ch);

        $uid=ApiModel::insertGetId($data);
        if($uid){
            //TODO
            $response=[
                'errno'=>0,
                'msg'=>'注册成功'
            ];
        }else{
            //TODO
            $response=[
                'errno'=>50003,
                'msg'=>'注册用户失败'
            ];
        }
        header('Refresh:3;url=http://client.1809a.com/userlogin');
        return(json_encode($response,JSON_UNESCAPED_UNICODE));
        //header('Refresh:3;url=http://client.1809a.com/userlogin');

    }
    public function UserLogin(Request $request){
        $email=$request->input('email');
        $pass=$request->input('password');
        $u=ApiModel::where(['email'=>$email])->first();
        if($u){      //用户存在
            if(password_verify($pass,$u->pass)){  //验证密码
                //TODO 登录逻辑
                $token=$this->UserToken($u->uid);
                $redis_token_key='user_token:uid:'.$u->uid;
                Redis::set($redis_token_key,$token);
                Redis::expire($redis_token_key,604800);
                //生成token
                $response=[
                    'errno'=>0,
                    'msg'=>'ok',
                    'data'=>[
                        'token'=>$token
                    ]
                ];
            }else{
                //TODO 登录失败
                $response=[
                    'errno'=>50010,
                    'msg'=>'密码不正确'
                ];
            }
            die(json_encode($response,JSON_UNESCAPED_UNICODE));
        }else{       //用户不存在
            $response=[
                'errno'=>50011,
                'msg'=>'用户不存在'
            ];
        }
        header('Refresh:3;url=http://client.1809a.com/userconter');
        return (json_encode($response,JSON_UNESCAPED_UNICODE));
    }
    protected function UserToken($uid){
        return substr(sha1($uid.time().Str::random(10)),5,15);
    }
    public function ones(){
        $data=ApiModel::get();
        $arr=[
            'data'=>$data,
        ];
        return $arr;die;
        $json_str=json_encode($arr);
        $k=openssl_get_privatekey('file://keys/rsa_private_key.pem');
//          echo $k;die;
        openssl_private_encrypt($json_str,$enc_data,$k);
        var_dump($enc_data);die;

        $api_url='http://client.1809a.com/useren';
        $ch=curl_init();
        curl_setopt($ch,CURLOPT_URL,$api_url);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$enc_data);
        curl_setopt($ch,CURLOPT_HTTPHEADER,[
            'Content-type:text/plain'
        ]);
        $response=curl_exec($ch);
        //监控错误码
        $err_code=curl_errno($ch);
        //var_dump($err_code);
        if($err_code>0){
            echo "CURL 错误码：".$err_code;exit;
        }
        curl_close($ch);
    }
}