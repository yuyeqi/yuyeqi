<?php


namespace App\Models;


/**
 *配置模型
 * Class Picture
 * @package App\Models
 */
class Config extends Base
{
    //定义模型关联表
    protected $table = 'hp_config';

    /**
     * 根据配置编号获取配置信息
     * @param $configNo
     * @return mixed
     */
    public static function getConfigByNo($configNo){
        $field = ['config_no','config_name','background','config_value','content'];
        $config = self::select($field)->where(['config_no'=>$configNo])->first();
        return $config;
    }

}
