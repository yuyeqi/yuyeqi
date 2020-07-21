<?php


namespace App\Models;


/**
 *钱包交易记录模型
 * Class Picture
 * @package App\Models
 */
class WalletDeal extends Base
{
    //定义模型关联表
    protected $table = 'hp_wallet_deal';

    //时间转换
    const CREATED_AT = 'create_time';
    const UPDATED_AT = null;

    //设置保存字段
    protected $guarded = [];

    /**
     * 钱包交易记录
     * @param $userInfo
     * @param string $field
     * @param $page
     * @param $limit
     * @return mixed
     */
    public static function getWalletList($userInfo, $field="*", $dealType, $page, $limit){
        $map = ['user_id'=>$userInfo['id'],'is_delete'=>0];
        $dealType > 0 && $map['deal_type'] = $dealType;
        return self::select($field)
            ->where($map)
            ->orderBy('id','desc')
            ->paginate($limit);
    }
}
