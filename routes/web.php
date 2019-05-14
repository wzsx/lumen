<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});
$router->post('/user','Users\UserController@info');
$router->get('/center','Users\UserController@uCenter');
$router->get('/order','Users\UserController@order');



$router->post('/user/1','Users\LoginController@login');
$router->get('/center/2','Users\LoginController@center');
$router->post('/order/3','Users\LoginController@order');


$router->post('/password','Users\UserController@info');
$router->get('/openssl','Openssl\OpensslController@openssl');
$router->post('/test/sec','Test\TestController@testSec');//对称
$router->post('/test/sss','Test\TestController@testSss');//非对称
$router->post('/test/sign','Test\TestController@testSign');//非对称  验签


//注册
$router->post('/user/reg','User\UserController@UserReg');
$router->post('/user/login','User\UserController@UserLogin');
$router->post('/user/ones','User\UserController@ones');

//api注册
$router->post('/register','Api\ApiController@register');
//api登录
$router->post('/login','Api\ApiController@login');
//api个人中心
$router->post('/center','Api\ApiController@center');
$router->get('/centers','Api\ApiController@centers')->middleware('check.login');