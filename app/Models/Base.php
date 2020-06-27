<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * 用户模型
 * Class User
 * @package App\Models
 */
class Base extends Model
{
    //时间转换
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';
    //时间格式
    protected $dateFormat = 'U';

    protected $guarded = [];

    protected  $hidden = [
        'is_delete','update_user_id','create_user_id','create_user_name'
    ];
    /**
     * 创建时间
     * @return false|string
     */
    public function getCreateTimeAttribute(){
        return date('Y-m-d H:i:s', $this->attributes['create_time']);
    }

    /**
     * 更新时间
     * @return false|string
     */
    public function getUpdateTimeAttribute(){
        return date('Y-m-d H:i:s', $this->attributes['update_time']);
    }


}
