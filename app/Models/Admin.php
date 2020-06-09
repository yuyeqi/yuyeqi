<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    //定义模型关联表
    protected $table = 'hp_admin';
    //时间转换
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';


    //后台用户列表
    public function getAdminLists($keyword,$status,$limit){
        //设置搜索条件
        $map = ['is_delete'=>0];
        if (isset($status) && !empty($status)){
            $map[] = ['status',$status];
        }
        if (isset($keyword) && !empty($keyword)){
            self::where(function ($query) use ($keyword){
                $query->where('username','like','%'.$keyword.'%')
                    ->orWhere('phone','like','%'.$keyword.'%')
                    ->orWhere('accunt','like','%'.$keyword.'%');
            });
        }
        //查询
        $field = ['id','username','phone','sex','account','status','is_login','update_user_name',
            'create_user_name','login_time','update_time','create_time'];
        $lists = self::where($map)
            ->whereIn('status',[0,1])
            ->select($field)
            ->orderBy('id','desc')
            ->paginate($limit);
        return $lists;
    }
}
