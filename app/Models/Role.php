<?php

namespace App\Models;

use Carbon\Carbon;

/**
 * 角色模型
 * Class Role
 * @package App\Models
 */
class Role extends Base
{
    //定义模型关联表
    protected $table = 'role';
    //时间转换
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';


}
