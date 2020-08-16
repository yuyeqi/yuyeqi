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
    protected $table = 'exchange_record';

    //设置保存字段
    protected $guarded = [];

    //时间转换
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';

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

    /**
     * 交易记录
     * @param $keywords
     * @param $page
     * @param $limit
     * @return mixed
     */
    public function getRecordList($keywords,$page,$limit){
        $map = ['exchange_record.is_delete'=>0];
        $field = ['exchange_record.*','user_address.consignee','user_address.phone','user_address.province',
            'user_address.city','user_address.area','user_address.address'];
        return self::select($field)
            ->where($map)
            ->when(!empty($keywords),function ($query) use ($keywords){
                return $query->where('exchange_record.deal_no','like','%'.$keywords.'%')
                    ->orWhere('exchange_record.user_name','like','%'.$keywords.'%')
                    ->orWhere('exchange_record.goods_name','like','%'.$keywords.'%');
            })
            ->leftJoin('user','exchange_record.user_id','=','user.id')
            ->leftJoin('user_address','user.delivery_id','=','user_address.id')
            ->orderBy('exchange_record.create_time','desc')
            ->paginate($limit);
    }

    /**
     * 更新状态
     * @param $data
     * @return mixed
     */
    public function updateStatus($data){
        return self::where(['id'=>$data['id']])->update($data);
    }

    /**
     * 批量删除
     * @param $ids
     * @param $data
     * @return mixed
     */
    public function delBatchRecord($ids,$data){
        return self::whereIn('id',$ids)->update($data);
    }
}
