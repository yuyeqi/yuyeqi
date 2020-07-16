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

    //设置保存字段
    protected $guarded = [];

}
