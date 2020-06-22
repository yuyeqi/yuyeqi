<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 新闻模型
 * Class News
 * @package App\Models
 */
class News extends Model
{    //定义模型关联表
    protected $table = 'hp_news';
    //时间转换
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';

    //时间格式
    protected $dateFormat = 'U';

    /*--------------------------------小程序----------------------------------*/

    /**
     * 小程序首页新品推荐
     * @return mixed
     */
    public function getNewsLists(){
        $map = ['status'=>10,'is_delete'=>0,'is_recommend'=>1];
        $field = ['id','news_name','update_time'];
        return self::select($field)->where($map)->orderBy('sort','desc')->take(4)->get();
    }

    /**
     * 小程序分页列表
     * @param $limit
     * @return mixed
     */
    public function getNewsPageLists($limit)
    {
        $map = ['status'=>10,'is_delete'=>0];
        $field = ['id','news_name','news_desc','news_cover','content','create_time'];
        return self::select($field)->where($map)->orderBy('sort','desc')->paginate($limit);
    }

    /**
     *新闻详情
     * @param $id
     * @return mixed
     */
    public static function getNewsDetail($id)
    {
        $map = ['status'=>10,'is_delete'=>0];
        $field = ['id','news_name','news_desc','read_num','content','create_time'];
        self::where('id',$id)->increment('read_num');
        return self::select($field)->where($map)->first();
    }
}
