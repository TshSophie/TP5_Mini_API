<?php
/**
 * Created by PhpStorm.
 * User: tsh
 * Date: 2019/2/1
 * Time: 21:27
 */

namespace app\api\exception;


class TokenException extends BaseException
{
    public $code = 401;
    public $msg = 'Token已经过期或者无效Token';
    public $error_code = 10001;

}