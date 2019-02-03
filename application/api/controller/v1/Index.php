<?php
/**
 * Created by PhpStorm.
 * User: tsh
 * Date: 2019/1/29
 * Time: 17:57
 */

namespace app\api\controller\v1;

use think\facade\Request;
use think\facade\Cache;
use app\api\model\Participant as UserModel;
class Index
{
    public function index()
    {
       return UserModel::all();

//        Cache::set('name',"hahahahaha",3600);
//        dump(Cache::get('name'));
//        dump(Request::header('token'));
//
//        dump(config('wx.app_id'));
    }
}