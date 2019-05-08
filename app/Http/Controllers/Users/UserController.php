<?php
namespace App\Http\Controllers\Users;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
class UserController extends Controller
{
    public $redis_h_u_key ='api:h:u:';
    public function info(Request $request){
//        $user=$request->input('post_data');
//        $time=$request->input('time');
//        $method="AES-128-CBC";
//        $salt="salt88";
//        $key="key";
//        $option=OPENSSL_RAW_DATA;
//        $iv=substr(md5($time.$salt),5,16);
//        $enc_str = openssl_encrypt($str,$method,$key,$option,$iv);
//print_r($_POST);die;
        $time=$_GET['t'];
        $key = 'pass';
        $api= 'AES-128-CBC';
        $salt ='sssss';
        $argc = OPENSSL_RAW_DATA;
        $iv=substr(md5($time.$salt),5,16);
        //签名
        $sign = base64_decode($_POST['sign']);
        $base64_data =$_POST['data'];
       // print_r($iv);

        //验签
        $pub_res= openssl_get_publickey(file_get_contents("./key/rsa_public_key.pem"));
        $rs=openssl_verify($base64_data,$sign,$pub_res,OPENSSL_ALGO_SHA256);
        //var_dump($rs);
        if(!$rs){
            //TODO  验签失败
            die;('验签失败');
        }
        $post_data = base64_decode($_POST['data']);
     //  print_r($post_data);die;
        $dec_str=openssl_decrypt($post_data,$api,$key,$argc,$iv);
       // print_r($dec_str);die;
        echo $dec_str;
        if(1){
            $time =time();
            $response =[
                'errno'=>0,
                'msg'=>'ok',
                'data'=>'this is secret'
            ];
            $iv2 = substr(md5($time.$salt),5,16);
            $enc_data = openssl_encrypt(json_encode($response),$api,$key,$argc,$iv2);
            $arr =[
                't' => $time,
                'data'=>base64_encode($enc_data)
            ];
            echo json_encode($arr);
        }
        // echo 1111;
//        $u=$request->input('u');
//        if($u){
//            $token=str_random(10);
//            $key=$this->redis_h_u_key.$u;
//            $redis_h_u_key=Redis::hSet($key,'token',$token);
//            Redis::expire($key,60*24*7);
//            $data=[
//                'errno'=>4001,
//                'msg'=>$token
//            ];
//        }else{
//            $data=[
//                'errno'=>5200,
//                'msg'=>'HTTP_TOKEN'
//            ];
//
//        }
//        $res=json_encode($data);
//        print_r($res);

    }
    public function uCenter(Request $request){
        $u=$request->input('u');
        if(empty($_SERVER['HTTP_TOKEN'])){
            $response =[
                'errno'=>50001,
                'msg'=>'Token Require!!'

            ];
        }else{

            $key =$this->redis_h_u_key.$u;
            $token =Redis::hGet($key,'token');
            //print_r($_SERVER['HTTP_TOKEN']);die;
            if($_SERVER['HTTP_TOKEN']!=$token){
                $response =[
                    'errno'=>50000,
                    'msg'=>'Not Token Require!!'

                ];
            }else{

                $response=[
                    'errno'=>0,
                    'msg'=>'ok',

                ];
            }


            $response=json_encode($response);
        }
        print_r($response);
    }

/**
 *防刷
 */
public function order(){

    $response=[
            'errno'=>0,
            'msg'=>'ok',
            'data'=>[
                'aaa'=>'bbbb'
            ]
      ];
    return json_encode($response);
}

}


