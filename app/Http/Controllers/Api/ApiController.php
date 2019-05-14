<?php
namespace App\Http\Controllers\Api;
use App\Model\ApiModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
class ApiController extends Controller{
    public function register(){
        $uname=trim($_POST['nick_name']);
        if(empty($uname)){
            $data=[
                'errcode'=>6001,
                'msg'=>'用户名不能为空'
            ];
            return $data;
        }elseif (strlen($uname)>10){
            $data=[
                'errcode'=>6002,
                'msg'=>'用户名最多10位'
            ];
            return $data;
        }
        $res_info=UserModel::where(['name'=>$uname])->first();
        if($res_info){
            $data=[
                'errcode'=>6003,
                'msg'=>'客观，您输入的账号已被注册！换一个呗。'
            ];
            return $data;
        }
        $upwd=$_POST['pass'];
        if(empty($upwd)){
            $data=[
                'errcode'=>6004,
                'msg'=>'密码不能为空'
            ];
            return $data;
        }
        $upwd2=$_POST['pass2'];
        if($upwd2!=$upwd){
            $data=[
                'errcode'=>6005,
                'msg'=>'密码和确认密码不一致'
            ];
            return $data;
        }
        $uemail=trim($_POST['email']);
        if(empty($uemail)){
            $data=[
                'errcode'=>6006,
                'msg'=>'邮箱不能为空'
            ];
            return $data;
        }elseif(substr_count($uemail,'@')==0){
            $data=[
                'errcode'=>6007,
                'msg'=>'邮箱格式不符合'
            ];
            return $data;
        }
        $uage=trim($_POST['age']);
        if(empty($utel)) {
            $data = [
                'errcode' => 6008,
                'msg' => '手机号不能为空'
            ];
            return $data;
        }
//        $utel=trim($_POST['utel']);
//        if(empty($utel)) {
//            $data = [
//                'errcode' => 6008,
//                'msg' => '手机号不能为空'
//            ];
//            return $data;
//        }elseif(!is_numeric($utel) || strlen($utel)!=11){
//            $data=[
//                'errcode'=>6009,
//                'msg'=>'手机号格式不符合'
//            ];
//            return $data;
//        }
        //nick_name:nick_name,age:age,pass2:pass2,email:email,pass:pass
        $info=[
            'name'=>$uname,
            'pass'=>$upwd,
            'age'=>$uage,
            'email'=>$uemail,
        ];
        $res=UserModel::insert($info);
        if($res){
            $data=[
                'errcode'=>0,
                'msg'=>'注册成功'
            ];
        }else{
            $data=[
                'errcode'=>5001,
                'msg'=>'注册失败'
            ];
        }
        return $data;
    }
}