<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 私人定制分类模型
 * Class Cases
 * @package App\Models
 */
class PersonCate extends Model
{    //定义模型关联表
    protected $table = 'hp_person_cate';
    //时间转换
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';


    /*--------------------------------小程序----------------------------------*/

    /**
     * 私人定制分类
     * @return mixed
     */
    public function getPersonCateLists(){
        $map = ['status'=>10,'is_delete'=>0];
        $field = ['id','cate_name','bg_url'];
        return self::select($field)->where($map)->orderBy('sort')->orderBy('id')->get();
    }

}
