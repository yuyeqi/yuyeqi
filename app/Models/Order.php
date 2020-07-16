<?php

namespace App\Models;


/**
 * 订单模型
 * Class Order
 * @package App\Models
 */
class Order extends Base
{    //定义模型关联表
    protected $table = 'hp_order';
    //时间转换
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';

    protected $guarded = [];
    //支付状态
    public function getPayStatusAttribute($value){
        $data = [
            '10'=>['status'=>10,'status_name'=>'未支付'],
            '20'=>['status'=>20,'status_name'=>'已支付'],
            '30'=>['status'=>30,'status_name'=>'退款']
        ];
        return $data[$value];
    }
    /**
     * 支付时间
     * @return false|string
     */
    public function getPayTimeAttribute(){
        return date('Y-m-d H:i:s', $this->attributes['create_time']);
    }

    /*------------------------------------------后端---------------------------------------------------*/
    /**
     * 订单列表
     * @param $id
     * @param $keywords
     * @param $pay_status
     * @param $startTime
     * @param $endTime
     * @param $limit
     * @return mixed
     */
    public function getLists($id,$keywords, $pay_status,$startTime,$endTime, $limit)
    {
        //查询
        $field = ['id', 'user_name', 'order_no', 'total_price', 'goods_price', 'pay_price', 'update_price', 'score',
            'pay_status', 'pay_time', 'transaction_id', 'is_comment', 'goods_name', 'goods_cover', 'create_time',
             'update_user_name', 'create_time', 'update_time','phone'];
        //搜索条件
        $map = ['is_delete' => 0];
        $pay_status > 0 && $map['pay_status'] = $pay_status;
        $id > 0 && $map['user_id'] = $id;
        $lists = self::where($map)
            ->when(!empty($keywords), function ($query) use ($keywords) {
                return $query->where('user_name', 'like', '%' . $keywords . '%')
                    ->orWhere('order_no', 'like', '%' . $keywords . '%')
                    ->orWhere('phone', 'like', '%' . $keywords . '%')
                    ->orWhere('goods_name', 'like', '%' . $keywords . '%');
            })
            ->when(!empty($startTime),function ($query) use ($startTime){
                return $query->whereDate('create_time','>',$startTime);
            })
            ->when(!empty($endTime),function ($query) use ($endTime){
                return $query->whereDate('create_time','<',$endTime);
            })
            ->select($field)
            ->orderBy('id', 'desc')
            ->paginate($limit);
        return $lists;
    }

    /**
     * 更新用户信息
     * @param $data
     * @return mixed
     */
    public function updateOrderInfoById($data)
    {
        return self::where(['id' => $data['id']])->update($data);
    }

    /**
     * 审核用户
     * @param $data
     * @return mixed
     */
    public function auditUser($data)
    {
        return self::where(['id' => $data['id']])->update($data);
    }

    /**
     * 更新用户状态
     * @param $data
     * @return mixed
     */
    public function updateUserStatus($data)
    {
        return self::where(['id' => $data['id']])->update($data);
    }

    /**
     * 订单详情
     * @param $id
     * @return mixed
     */
    public static function getOrderDetail($id)
    {
        $map = ['is_delete' => 0, 'id' => $id];
        return self::where($map)->first();
    }

    /**
     * 批量删除
     * @param $data
     * @param array $ids
     * @return mixed
     */
    public function delBatch($data, array $ids)
    {
        return self::whereIn('id', $ids)->update($data);
    }

    /**
     * 更新状态
     * @param $data
     * @return mixed
     */
    public function updateStatus($data){
        return self::where(['id'=>$data['id']])->update($data);
    }
    /*---------------------------------------------小程序----------------------------------------------*/
    /**
     * 用户信息
     * @return mixed
     */
    public function getUserInfo($id)
    {
        $map = ['status' => 0, 'is_delete' => 0, 'id' => $id];
        $field = ['id', 'nick_name', 'avatar_url', 'user_type'];
        return self::select($field)->where($map)->with('userStatistic')->first();
    }


    /**
     * 创建订单
     * @param array $data
     * @return mixed
     */
    public function createOrder(array $data)
    {
        return self::create($data);
    }

}
