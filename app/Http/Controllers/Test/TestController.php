<?php
namespace App\Http\Controllers\Test;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redis;
class TestController extends Controller{
    public function testSec(){
        $enc_str=file_get_contents("php://input");
        echo $enc_str;echo '<hr>';
        //解密
        $method ='AES-256-CBC';
        $key='yufsfs';
        $iv ='1234567890asdfgh';
        $d64=base64_decode($enc_str);
        $dec_data=openssl_decrypt($d64,$method,$key,OPENSSL_RAW_DATA,$iv);
        echo $dec_data;die;
        //TODO//业务逻辑
    }
    public function testSss(){
        $enc_str=file_get_contents("php://input");
        echo $enc_str;
        //echo '<hr>';

        //解密
        $pk=openssl_get_publickey('file://keys/rsa_public_key.pem');
        //echo $pk;die;
        openssl_public_decrypt($enc_str,$dnc_data,$pk);
        echo '<hr>';
        echo $dnc_data;
    }
    public function testSign(){
        echo '<pre>';print_r($_GET);echo '</pre>';
        $str=file_get_contents("php://input");
        echo 'json:'.$str;echo'</br>';echo'<hr>';
        $rec_sign=$_GET['sign'];
        $pk=openssl_get_publickey('file://keys/rsa_public_key.pem');
        //验签
        $rs=openssl_verify($str,base64_decode($rec_sign),$pk);
        var_dump($rs);

    }
}