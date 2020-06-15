<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Picture extends Model
{
    //定义模型关联表
    protected $table = 'hp_picture';
    //时间转换
    const CREATED_AT = 'create_time';

    //隐藏字段
    protected $hidden = ['is_delete'];
}
