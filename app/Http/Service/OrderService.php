<?php


namespace App\Http\Service;


use App\Models\Goods;
use App\Models\Order;
use App\Models\User;
use App\Models\UserStatistic;
use Illuminate\Support\Facades\DB;

/**
 * 用户服务层
 * Class SlideshowService
 * @package App\Http\Service
 */
class OrderService extends BaseSerivce
{
    private $order; //订单模型
    private $goods; //商品模型

    /**
     * OrderService constructor.
     */
    public function __construct()
    {
        $this->order = isset($this->order) ?: new Order();
        $this->goods = isset($this->goods) ?: new Goods();
    }

    /**
     * 订单列表
     * * @param $id
     * @param $keywords
     * @param $userType
     * @param $status
     * @param $auditStatus
     * @param $limit
     * @return mixed
     */
    public function getLists($id,$keywords, $pay_status,$startTime,$endTime, $limit){
        return $this->order->getLists($id,$keywords, $pay_status,$startTime,$endTime, $limit);
    }

    /**
     * 修改用户信息
     * @param $data
     * @param $loginInfo
     * @return bool
     */
    public function edit($data,$loginInfo){
        //订单详情
        $detail = Order::getOrderDetail($data['id']);
        if ($detail->pay_status != 10){
            $this->setErrorMsg("只能修改未支付的订单");
            return false;
        }
        //订单数据
        $data['update_user_id'] = $loginInfo['id'];;
        $data['update_user_name'] = $loginInfo['username'];
        return  $this->order->updateOrderInfoById($data);
    }

    /**
     * 审核用户
     * @param $data
     * @return mixed
     */
    public function userAudit($data,$loginInfo){
        $data['update_user_id'] = $loginInfo['update_user_id'];
        $data['update_user_name'] = $loginInfo['update_user_name'];
        return $this->user->auditUser($data);
    }

    /**
     * 删除
     * @param array $ids
     * @param $loginInfo
     * @return mixed
     */
    public function delBatch(array $ids,$loginInfo)
    {
        $data['update_user_id'] = $loginInfo['id'];;
        $data['update_user_name'] = $loginInfo['username'];
        $data['is_delete'] = 1;
        return  $this->order->delBatch($data,$ids);
    }
    /**
     * 后端详情
     * @param $id
     * @return mixed
     */
    public function getUserDetail($id)
    {
        return $this->user->getUserDetail($id);
    }

    /**
     * 更新用户状态
     * @param $data
     * @param $loginInfo
     * @return mixed
     */
    public function updateStatus($data,$loginInfo){
        $data['update_user_id'] = $loginInfo['id'];;
        $data['update_user_name'] = $loginInfo['username'];
        return $this->user->updateStatus($data);
    }
    /*-----------------------------小程序端-------------------------------------*/
    /**
     * 小程序用户信息
     * @return mixed
     */
    public function getUserInfo($id){
        return $this->user->getUserInfo($id);
    }

    public function createOrder(array $data, $userInfo)
    {
        //商品信息
        $goodsDetail = $this->goods->getApiGoodsDetailById($data["goods_id"]);
        //检测商品是否存在
        if ($goodsDetail["goods_status"]['status'] != 10 || $goodsDetail["is_delete"] != 0){
            $this->setErrorMsg("商品已下架或不存在");
            return false;
        }
        //订单数据
        $data['user_id'] = $userInfo['id'];
        $data['user_name'] = $userInfo['user_name'];
        $data['phone'] = $userInfo['phone'];
        $data['order_no'] = $this->getOrderNo();
        $data['total_price'] = $userInfo['user_name'];
        $data['total_price'] = $goodsDetail['book_price'];
        $data['goods_id'] = $goodsDetail['id'];
        $data['goods_price'] = $goodsDetail['good_price'];
        $data['score'] = $goodsDetail['score'];
        $data['buyer_remark'] = $data['buyer_remark'];
        $data['goods_name'] = $goodsDetail['goods_name'];
        $data['goods_cover'] = $goodsDetail['goods_cover'];
        $data['create_time'] = time();
        return $this->order->createOrder($data);
    }

    /**
     * @return string生成订单号
     */
    private function getOrderNo(){
        return date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    }


}
