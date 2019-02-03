<?php
/**
 * Created by PhpStorm.
 * User: tsh
 * Date: 2019/2/2
 * Time: 18:22
 */

namespace app\api\exception;


class WeChatException extends BaseException
{
    public $code = 404;
    public $msg = '微信接口错误';
    public $errorCode = 9999;


}