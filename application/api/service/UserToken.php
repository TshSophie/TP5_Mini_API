<?php
/**
 * Created by PhpStorm.
 * User: tsh
 * Date: 2019/1/29
 * Time: 17:04
 */

namespace app\api\service;

use think\Exception;
use app\api\exception\TokenException;
use app\api\model\User as UserModel;
use app\api\enum\ScopeEnum;
use app\api\exception\WeChatException;
class UserToken extends Token
{
    protected $code;   // 前端获取的登录凭证
    protected $wxAppID; // 小程序id
    protected $wxAppSecret; // 小程序秘钥
    protected $wxLoginUrl; // 微信登录链接

    public function __construct($code)
    {
        $this->code=$code;
        $this->wxAppID = config('wx.app_id');
        $this->wxAppSecret = config('wx.app_secret');
        $this->wxLoginUrl = sprintf(config('wx.login_url'),$this->wxAppID,$this->wxAppSecret,$this->code);
    }

    /**
     * 先微信发送请求获取openid并生成token
     * @return string token令牌
     * @throws Exception
     */
    public function get()
    {
        //向微信发送请求
        $result = curl_get($this->wxLoginUrl);
        //将返回结果转成数组
        $wxResult = json_decode($result,true);

        //如果请求结果为空则抛出服务器异常
        if (empty($wxResult)){

            throw new Exception('获取session_key及openID时异常，微信内部错误');
        }
        else
        {
            //微信请求 结果为失败抛出异常
            $loginFail = array_key_exists('errcode',$wxResult);
            if ($loginFail)
            {
                //处理异常错误
                $this->processLoginError($wxResult);
            }
            else
            {
                //成功返回token
                return $this->grantToken($wxResult);
            }
        }

    }

    /**
     * 根据微信返回的信息获取token
     * @param $wxResult
     * @return string
     */
    private function grantToken($wxResult)
    {
        //拿到openid
        //去数据库中看一下，这个openid是否已经存在
        //如果存在，则不处理，否则新增一条user记录
        //生成令牌，准备缓存数据，写入缓存
        //把令牌返回到客户端去
        //key:令牌
        //value：wxResult, uid ,scope权限
        $openid = $wxResult['openid'];
        //获取openid对应用户信息
        $user = UserModel::getByOpenID($openid);
        //若存在该用户则取出uid
        if ($user)
        {
            $uid = $user->id;
        }
        //否则新增一个用户并将uid传过来,并抛出未授权的异常
        else{
            $uid = $this->newUser($openid);
        }
        //预处理缓存值
        $cachedValue = $this->prepareCachedValue($wxResult,$uid);
        //将处理好的信息存入缓存并返回token
        $token = $this->saveToCache($cachedValue);
        return $token;
    }


    /**
     *  缓存用户信息并返回生成的token
     * @param $cachedValue 需要缓存的信息
     * @return string    token令牌
     * @throws ThemeException
     */
    private function saveToCache($cachedValue)
    {
        //生成token
        $key = self::generateToken();
        $value = json_encode($cachedValue);
        $expire_in = config('setting.token_expire_in');
        //缓存
        $request = cache($key,$value,$expire_in);
        if (!$request)
        {
            throw new TokenException([
                'msg'=>'服务器缓存异常',
                'error_code'=>10005
            ]);
        }
        //返回令牌
        return $key;
    }


    /**
     * 预处理缓存信息并将其返回
     * @param $wxResult 微信返回的结果
     * @param $uid    用户id
     * @return mixed 返回处理后的信息
     */
    private function prepareCachedValue($wxResult,$uid)
    {
        $cachedValue = $wxResult;
        $cachedValue['uid'] = $uid;
        $cachedValue['scope'] = ScopeEnum::User;
        return $cachedValue;
    }


    /**
     * 根据openid来创建一个用户
     * @param $openid
     * @return mixed 用户id
     */
    private function newUser($openid)
    {
        $user = UserModel::create([
            'openid'=>$openid
        ]);
        return $user->id;
    }

    /**
     * 对微信返回的错误信息进行处理
     * @param $wxResult
     * @throws WeChatException
     */
    private function processLoginError($wxResult){

        throw new WeChatException(
            [
                'msg'=>$wxResult['errmsg'],
                'error_code'=>$wxResult['errcode']
            ]);

    }
}

