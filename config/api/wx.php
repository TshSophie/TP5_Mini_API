<?php
/**
 * Created by PhpStorm.
 * User: tsh
 * Date: 2019/1/29
 * Time: 17:46
 */

return [
    'app_id'=>'',
    'app_secret'=>'',
    'login_url'=>"https://api.weixin.qq.com/sns/jscode2session?".
        "appid=%s&secret=%s&js_code=%s&grant_type=authorization_code"
];