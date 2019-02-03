<?php
/**
 * Created by PhpStorm.
 * User: tsh
 * Date: 2019/2/1
 * Time: 23:09
 */

namespace app\api\validate;


class TokenGetValidate extends BaseValidate
{
    //验证规则
    protected $rule = [
        'code'=>'require|isNotEmpty'
    ];

    //定义错误信息
    protected $message = [
        'code'=>'没有code还想获取token，搞笑！'
    ];
}