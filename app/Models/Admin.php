<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Admin extends Model
{
    //定义模型关联表
    protected $table = 'hp_admin';
    //时间转换
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';

    //登录时间获取器
    public function getLoginTimeAttribute(){
        return date('Y-m-d H:i:s', $this->attributes['login_time']);
    }

    //后台用户列表
    public function getAdminLists($keyword,$limit){
        //查询
        $field = ['id','username','phone','sex','account','status','is_login','update_user_name',
            'create_user_name','login_time','update_time','create_time'];
        $lists = self::where(['is_delete'=>0])
            ->when($keyword != '',function ($query) use ($keyword){
                return $query->where('username','like','%'.$keyword.'%')
                    ->orWhere('phone','like','%'.$keyword.'%')
                    ->orWhere('account','like','%'.$keyword.'%');
            })
            ->whereIn('status',[0,1])
            ->select($field)
            ->paginate($limit);
        return $lists;
    }
}
