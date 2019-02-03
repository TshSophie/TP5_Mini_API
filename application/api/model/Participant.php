<?php
/**
 * Created by PhpStorm.
 * User: tsh
 * Date: 2019/2/1
 * Time: 21:24
 */

namespace app\api\model;

use think\Model;

class Participant extends Model
{
    protected $pk = 'ParticipantID';

    public static function getByOpenID($openid)
    {
        return self::where('openid','=',$openid)->select();
    }
}