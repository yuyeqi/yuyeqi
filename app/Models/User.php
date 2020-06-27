<?php

namespace App\Models;


/**
 * 用户模型
 * Class User
 * @package App\Models
 */
class User extends Base
{    //定义模型关联表
    protected $table = 'hp_user';
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
    public function getUserInfo($id){
        $map = ['status'=>0,'is_delete'=>0,'id'=>$id];
        $field = ['id','nick_name','avatar_url','user_type'];
        return self::select($field)->where($map)->with('userStatistic')->first();
    }

}
