<?php
/**
 * Created by PhpStorm.
 * User: tsh
 * Date: 2019/1/29
 * Time: 16:58
 */
namespace app\api\service;

use think\facade\Cache;
use think\Exception;
use think\facade\Request;
use app\api\exception\TokenException;
class Token
{
    /**
     * 生成令牌
     * @return string 返回生成的令牌
     */
    public static function generateToken()
    {
        //32个字符组成一组随机字符串
        $randChars = get_rand_chars(32);
        //用三组字符串，进行MD5加密
        $timestamp = $_SERVER['REQUEST_TIME'];
        //salt加盐加密
        $salt = config('secure.token_salt');
        return md5($randChars . $timestamp . $salt);
    }

    /**
     * 从缓存中获取指定字段的值
     * @param $key 字段名
     * @return mixed 字段值
     * @throws Exception
     * @throws TokenException
     */
    public static function getCurrentTokenVar($key,$p_token='')
    {
        if($p_token == '')
        {
            //获取header中的token参数
            $token = Request::header('token');
        }
        else
        {
            $token = $p_token;
        }

        //根据token获取缓存信息
        $vars = Cache::get($token);
        if (!$vars) {
            throw new TokenException();
        } else {
            //如果缓存信息不是json格式则转成数组格式
            if (!is_array($vars)) {

                $vars = json_decode($vars, true);
            }
            //检查缓存中是否存在$key这个键，存在则取出并返回
            if (array_key_exists($key, $vars)) {

                return $vars[$key];

            } else {

                throw new Exception('尝试获取的Token变量不存在');
            }
        }
    }


    /**
     * 获取当前用户的uid
     * @return mixed
     */
    public static function getCurrentUid()
    {
        //token
        $uid = self::getCurrentTokenVar('uid');
        return $uid;
    }
}
