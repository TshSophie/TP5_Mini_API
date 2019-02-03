<?php
/**
 * Created by PhpStorm.
 * User: tsh
 * Date: 2019/2/2
 * Time: 18:34
 */

namespace app\api\model;


use think\Model;

class User extends Model
{
    protected $pk = 'id';

    public static function getByOpenID($openid)
    {
        return self::where('openid','=',$openid)->find();
    }
}