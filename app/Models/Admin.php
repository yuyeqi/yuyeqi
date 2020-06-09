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
    public function getAdminLists($search){
        //设置搜索条件
        $map = ['is_delete'=>0];
        if (isset($search['status']) && !empty($search['username'])){
            $map[] = ['status',$search['status']];
        }
        if (isset($search['keywords']) && !empty($search['keywords'])){
            $keywords = $search['keywords'];
            self::where(function ($query) use ($keywords){
                $query->where('username','like','%'.$keywords.'%')
                    ->orWhere('phone','like','%'.$keywords.'%')
                    ->orWhere('accunt','like','%'.$keywords.'%');
            });
        }
        //查询
        $field = ['id','username','phone','sex','account','status','is_login','update_user_name',
            'create_user_name','login_time','update_time','create_time'];
        $lists = self::where($map)->orderBy('id','desc')->select($field)->paginate(10);
        return $lists;
    }
}
