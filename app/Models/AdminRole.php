<?php

namespace App\Models;

use Carbon\Carbon;

/**
 * 用户角色模型
 * Class AdminRole
 * @package App\Models
 */
class AdminRole extends Base
{
    //定义模型关联表
    protected $table = 'admin_role';

    public $timestamps = false;


}
