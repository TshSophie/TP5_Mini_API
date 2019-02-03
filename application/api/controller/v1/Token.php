<?php
/**
 * Created by PhpStorm.
 * User: tsh
 * Date: 2019/2/1
 * Time: 22:57
 */

namespace app\api\controller\v1;

use app\api\service\UserToken;
use app\api\service\Token as TokenService;
use app\api\validate\TokenGetValidate;
use app\api\model\User as UserModel;
use app\api\enum\ErrorEnum;
use app\api\exception\SuccessMessage;
class Token
{
    public function getToken($code='')
    {
        //参数验证
        (new TokenGetValidate())->goCheck();
        //根据临时登录凭证code实例化用户token处理类
        $ut = new UserToken($code);
        //调用获取token的类
        $token = $ut->get();
        //判断该用户是否已经授权过
        //查询出uid
        $uid =  TokenService::getCurrentTokenVar('uid',$token);
        //根据缓存中的uid查询该用户信息
        $user = UserModel::get($uid);
        //如果没有查到，表明该用户还未授权，抛出异常
//        if (!$user->nickname){
//
//            throw new SuccessMessage(['msg'=>'用户还未授权信息','error_code'=>ErrorEnum::USER_NOT_AUTHORIZATION]);
//        }

        //返回token
        //同时将用户的头像昵称信息传给前端
        return json([
            'token'=>$token,
            'userInfo'=>$user,
            'error_code'=>0
        ]);

    }

}