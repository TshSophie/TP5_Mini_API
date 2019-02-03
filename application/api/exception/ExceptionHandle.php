<?php
/**
 * Created by PhpStorm.
 * User: tsh
 * Date: 2019/2/2
 * Time: 14:47
 */

namespace app\api\exception;

use think\facade\Request;
use think\exception\Handle;
use think\facade\Log;

class ExceptionHandle extends Handle
{
    private $code;
    private $msg;
    private $error_code;
    // 需要返回客户端当前请求的URL路径

    //所有抛出的异常都会通过render方法来渲染
    public function render(\Exception $e)
    {

        if($e instanceof BaseException)
        {   //如果是自定义的异常
            $this->code = $e->code;
            $this->msg = $e->msg;
            $this->error_code = $e->error_code;

        }else{
            //从配置文件中获取当前是否是在开发阶段调试模式下
            if (config('app_debug'))
            {
                //还原tp5默认的render方法,即调用父类的render方法
                return parent::render($e);

            }else{

                $this->code = 500;
                $this->msg = '服务器内部错误，不想告诉你';
                $this->error_code = 999;
                $this->recordErrorLog($e); // 记录日志
            }
        }

        $result = [
            'msg'=>$this->msg,
            'error_code'=>$this->error_code,
            'request_url'=>Request::url()
        ];

        return json($result,$this->code);
    }

    private function recordErrorLog(\Exception $e)
    {
        //初始化日志
        Log::init([
            'type'=>'File',
            'path'=>LOG_PATH,
            'level'=>['error']
        ]);
        //第一个参数为错误信息，第二个参数为错误的级别
        Log::record($e->getMessage(),'error');
    }

}