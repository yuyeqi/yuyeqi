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

    //时间转换
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';

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

    /**
     * 配置列表
     * @param $keyword
     * @param $limit
     * @return mixed
     */
    public function getConfigLists($keyword,$limit){
        //查询
        $field = ['*'];
        $lists = self::select($field)
            ->when(!empty($keyword),function ($query) use ($keyword){
                return $query->where('config_no','like','%'.$keyword.'%')
                    ->orWhere('config_name','like','%'.$keyword.'%');
            })
            ->orderBy('update_time','desc')
            ->paginate($limit);
        return $lists;
    }

    /**
     * 修改配置
     * @param $data
     * @return mixed
     */
    public function edit($data){
        return self::where(['config_no'=>$data['config_no']])->update($data);
    }

    /**
     * 配置详情
     * @param $configNo
     * @return mixed
     */
    public static function getConfigDetail($configNo){
        return self::where(['config_no'=>$configNo])->first();
    }
}
