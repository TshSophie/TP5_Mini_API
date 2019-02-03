<?php
/**
 * Created by PhpStorm.
 * User: tsh
 * Date: 2019/2/1
 * Time: 21:27
 */

namespace app\api\exception;

use think\Exception;

class BaseException extends Exception
{
    //HTTP 状态码 404,200
    public $code = 400;
    //错误体信息
    public $msg = '参数错误';
    //自定义的错误码
    public $error_code = 10000;

    public  function __construct($param = [])
    {
        if (!is_array($param))
        {
            //throw new Exception('参数必须是数组');
            return ;
        }else{

            //判断数组中是否有该元素
            if (array_key_exists('code',$param))
            {
                $this->code = $param['code'];
            }
            if (array_key_exists('msg',$param))
            {
                $this->msg = $param['msg'];
            }
            if (array_key_exists('error_code',$param))
            {
                $this->error_code = $param['error_code'];
            }
        }

    }
}