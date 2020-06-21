<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Slideshow extends Model
{
    //定义模型关联表
    protected $table = 'ph_slideshow';
    //时间转换
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';

    /**
     * 首页轮播图列表
     * @return int
     */
     public function getSlideshowLists(){
         return self::where(['status'=>0,'is_delete'=>0])->orderBy('id','desc')->take(5)->get();
     }
}
