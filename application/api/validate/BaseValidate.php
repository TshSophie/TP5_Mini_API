<?php
/**
 * Created by PhpStorm.
 * User: tsh
 * Date: 2019/2/1
 * Time: 23:00
 */

namespace app\api\validate;

use think\facade\Request;
use app\api\exception\ParameterException;
use think\Validate;

class BaseValidate extends Validate
{
    /**
     * 验证参数
     * @return bool
     * @throws ParameterException
     */
    public function goCheck()
    {
        //获取http传入参数
        //对这些参数做检验
        $params = Request::param();

        //批量验证
        $result = $this->batch()->check($params);

        if (!$result){
            //dump($this->error);
            //使用自定义的异常类来处理异常
            throw new ParameterException(['msg'=>$this->error]);

        }else{

            return true;
        }

    }

    /**
     * 验证header参数
     */
    protected function checkHeader()
    {
        //获取header中的参数
        $params = Request::header();
        //批量验证
        $result = $this->batch()->check($params);

        if (!$result){

            //使用自定义的异常类来处理异常
            throw new ParameterException(['msg'=>$this->error]);
        }else{

            return true;
        }

    }

    //验证参数是否是正整数的方法
    protected function isPositiveInteger($value,$rule='',$data='',$field='')
    {

        if (is_numeric($value) && is_int($value + 0) && ($value + 0) > 0){

            return true;

        }else{

            return false;
        }
    }


    /**
     * 校验是否为空
     * @param $value
     * @param string $rule
     * @param string $data
     * @param string $field
     * @return bool
     */
    protected function isNotEmpty($value,$rule='',$data='',$field='')
    {
        if (empty($value))
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    /**
     * 防止客户端恶意传递非法字段修改数据库信息，读取并返回验证类中设置的验证规则
     * @param $arrays 需要校验的参数数组
     * @return array
     * @throws ParameterException
     */
    public function getDataByRule($arrays)
    {
        //如果该数组中包含user_id或uid键名则抛出异常
        if (array_key_exists('user_id',$arrays)|array_key_exists('uid',$arrays)){
            //不允许包含user_id或者uid,防止恶意覆盖user_id外键
            throw new ParameterException(['msg'=>'参数中包含有非法的参数名user_id或uid']);
        }

        $newArray = [];
        //遍历验证类中定义的验证规则
        foreach ($this->rule as $key => $value){
            $newArray[$key] = $arrays[$key];
        }
        return $newArray;
    }

    /**
     * 手机号校验
     * @param $value
     * @return bool
     */
    public function isMobile($value)
    {
        $rule = '^1(3|4|5|7|8)[0-9]\d{8}$^';
        $result = preg_match($rule,$value);
        if ($result){
            return true;
        }else{
            return false;
        }
    }
}