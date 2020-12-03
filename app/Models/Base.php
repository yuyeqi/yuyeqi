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

    protected $guarded = [];

    protected  $hidden = [
        'is_delete','update_user_id','create_user_id','create_user_name'
    ];

}
