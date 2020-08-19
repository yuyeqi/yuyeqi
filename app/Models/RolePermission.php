<?php

namespace App\Models;

use Carbon\Carbon;

/**
 * 角色权限模型
 * Class RolePermission
 * @package App\Models
 */
class RolePermission extends Base
{
    //定义模型关联表
    protected $table = 'role_permission';

    public $timestamps = false;


}
