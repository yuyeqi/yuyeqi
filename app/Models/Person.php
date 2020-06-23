<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 私人定制模型
 * Class Cases
 * @package App\Models
 */
class Person extends Model
{    //定义模型关联表
    protected $table = 'hp_person';
    //时间转换
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';

    //设置保存字段
    protected $guarded  = ['is_audit','is_delete',];

    /*--------------------------------小程序----------------------------------*/
    /**
     * 查询本月是否已经提交过私人定制计划
     * @param $userId
     * @return mixed
     */
    public function getMonthPerson($userId)
    {
        //当月
        $month = date('Y-m',time());
        $map = ['user_id'=>$userId,'is_delete'=>0];
        return self::where($map)->whereIn('is_audit',[10,20])->whereMonth('create_time',"6")->count();
    }

    /**
     * 提交私人定制
     * @param $data
     * @return mixed
     */
    public function addPerson($data){
        return self::create($data);
    }

}
