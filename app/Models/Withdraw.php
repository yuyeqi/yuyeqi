<?php


namespace App\Models;


/**
 *用户提现模型
 * Class Picture
 * @package App\Models
 */
class Withdraw extends Base
{
    //定义模型关联表
    protected $table = 'hp_withdraw';

    //设置保存字段
    protected $guarded = [];

    //时间转换
    const CREATED_AT = 'create_time';
    const UPDATED_AT = null;
    /**
     * 提心记录
     * @param $userInfo
     * @param string $field
     * @param $status
     * @param $limit
     * @return mixed
     */
    public static function getCushLists($userInfo, $field="*", $status, $page, $limit){
        $map = ['user_id'=>$userInfo['id'],'is_delete'=>0];
        $status > 0 && $map['status'] = $status;
        return self::select($field)
            ->where($map)
            ->orderBy('id','desc')
            ->paginate($limit);
    }
}
