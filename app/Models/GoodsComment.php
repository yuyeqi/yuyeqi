<?php


namespace App\Models;


/**
 * 商品评论模型
 * Class Goods
 * @package App\Models
 */
class GoodsComment extends Base
{
    //定义模型关联表
    protected $table = 'hp_goods_comment';
    //时间转换
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';

    //隐藏字段
    protected $hidden = ['is_delete'];

    /**
     * 关联用户
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function user(){
        return $this->hasOne('App\Models\User','id','user_id')
            ->select(['id','nick_name','avatar_url'])
            ->where(['is_delete'=>0]);
    }
}
