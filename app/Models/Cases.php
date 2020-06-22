<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 案例模型
 * Class Cases
 * @package App\Models
 */
class Cases extends Model
{    //定义模型关联表
    protected $table = 'hp_cases';
    //时间转换
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';


    /*--------------------------------小程序----------------------------------*/

    /**
     * 小程序首页案例
     * @return mixed
     */
    public function getCaseLists($limit){
        $map = ['status'=>10,'is_delete'=>0];
        $field = ['id','case_name','case_cover'];
        return self::select($field)->where($map)->orderBy('sort','desc')->paginate($limit);
    }

    /**
     * 案例详情
     * @param $id
     * @return mixed
     */
    public static function getCasesDetail($id)
    {
        $map = ['status'=>10,'is_delete'=>0];
        $field = ['id','case_name','case_desc','content','create_time'];
        return self::select($field)->where($map)->first();
    }
}
