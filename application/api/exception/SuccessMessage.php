<?php
/**
 * Created by PhpStorm.
 * User: tsh
 * Date: 2019/2/2
 * Time: 17:06
 */

namespace app\api\exception;


class SuccessMessage extends BaseException
{
    public $code = 201;
    public $msg = 'ok';
    public $error_code = 0;
}