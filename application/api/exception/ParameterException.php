<?php
/**
 * Created by PhpStorm.
 * User: tsh
 * Date: 2019/2/1
 * Time: 23:02
 */

namespace app\api\exception;

use think\Exception;
class ParameterException extends BaseException
{
    public $code = 400;
    public $msg = '参数错误';
    public $error_code = 10000;

}