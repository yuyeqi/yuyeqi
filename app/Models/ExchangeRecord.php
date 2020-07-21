<?php


namespace App\Models;


/**
 *商品兑换记录
 * Class Picture
 * @package App\Models
 */
class ExchangeRecord extends Base
{
    //定义模型关联表
    protected $table = 'hp_exchange_record';

    //设置保存字段
    protected $guarded = [];

    //时间转换
    const CREATED_AT = 'create_time';
    const UPDATED_AT = null;

    /**
     * 商品兑换记录
     * @param $userInfo
     * @param string $field
     * @param $page
     * @param $limit
     * @return mixed
     */
    public static function getExRecordLists($userInfo, $field="*", $page, $limit){
        $map = ['user_id'=>$userInfo['id'],'is_delete'=>0];
        return self::select($field)
            ->where($map)
            ->orderBy('id','desc')
            ->paginate($limit);
    }

}
