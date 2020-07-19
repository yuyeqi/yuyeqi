<?php


namespace App\Models;


/**
 *推广人模型
 * Class Picture
 * @package App\Models
 */
class Promoter extends Base
{
    //定义模型关联表
    protected $table = 'hp_promoter';

    //设置保存字段
    protected $guarded = [];

    /**
     * 推广用户列表
     * @param $userInfo
     * @param $field
     * @param $page
     * @param $limit
     * @return mixed
     */
    public static function getPromoterLists($userInfo, $field, $page, $limit)
    {
        $map = ['promoter_id'=>$userInfo['id'],'is_delete'=>0];
        return self::select($field)
            ->where($map)
            ->orderBy('id','desc')
            ->paginate($limit);
    }


}
