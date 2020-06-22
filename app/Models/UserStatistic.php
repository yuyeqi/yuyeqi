<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * 用户数据统计模型
 * Class UserStatistic
 * @package App\Models
 */
class UserStatistic extends Model
{
    //定义模型关联表
    protected $table = 'ph_user_statistic';

}
