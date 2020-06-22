<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * 商品评论模型
 * Class Goods
 * @package App\Models
 */
class Goods extends Model
{
    //定义模型关联表
    protected $table = 'hp_goods_comment';
    //时间转换
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';

    //隐藏字段
    protected $hidden = ['is_delete'];
}
