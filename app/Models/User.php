<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class User extends Model
{    //定义模型关联表
    protected $table = 'ph_user';
    //时间转换
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';

    public function userStatistic(){
        return $this->hasOne('App\Models\UserStatistic','user_id','id')
            ->select(['id','user_id','amount','score']);
    }

    /*---------------------------------------------小程序----------------------------------------------*/
    /**
     * 用户信息
     * @return mixed
     */
    public function getUserInfo(){
        $map = ['status'=>0,'is_delete'=>0];
        $field = ['id','nick_name','avatar_url','user_type'];
        return self::select($field)->where($map)->with('userStatistic')->first();
    }
}
