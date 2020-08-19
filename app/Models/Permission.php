<?php

namespace App\Models;

use Carbon\Carbon;

/**
 * 权限模型
 * Class Permission
 * @package App\Models
 */
class Permission extends Base
{
    //定义模型关联表
    protected $table = 'permission';
    //时间转换
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';


}
