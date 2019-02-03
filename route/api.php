<?php
/**
 * Created by PhpStorm.
 * User: tsh
 * Date: 2019/2/2
 * Time: 17:18
 */

// 测试
Route::get('api/:version/index','api/:version.Index/index');


// 用户登陆，获取token
Route::post('api/:version/token/user','api/:version.Token/getToken');
