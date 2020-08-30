<?php


namespace App\Http\Service;


use App\Models\Goods;
use App\Models\GoodsComment;
use App\Models\Order;
use App\Models\Picture;
use App\Models\User;
use App\Models\UserStatistic;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * 用户服务层
 * Class SlideshowService
 * @package App\Http\Service
 */
class OrderService extends BaseSerivce
{
    private $order; //订单模型
    private $goods; //商品模型
    private $comment;   //商品评价模型
    private $picture;   //图片模型

    /**
     * OrderService constructor.
     */
    public function __construct()
    {
        $this->order = isset($this->order) ?: new Order();
        $this->goods = isset($this->goods) ?: new Goods();
        $this->comment = isset($this->comment) ?: new GoodsComment();
        $this->picture = isset($this->picture) ?: new Picture();
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
    public function getLists($id, $keywords, $pay_status, $startTime, $endTime, $limit)
    {
        return $this->order->getLists($id, $keywords, $pay_status, $startTime, $endTime, $limit);
    }

    /**
     * 修改用户信息
     * @param $data
     * @param $loginInfo
     * @return bool
     */
    public function edit($data, $loginInfo)
    {
        //订单详情
        $detail = Order::getOrderDetail($data['id']);
        if ($detail->pay_status != 10) {
            $this->setErrorMsg("只能修改未支付的订单");
            return false;
        }
        //订单数据
        $data['update_user_id'] = $loginInfo['id'];;
        $data['update_user_name'] = $loginInfo['username'];
        return $this->order->updateOrderInfoById($data);
    }

    /**
     * 审核用户
     * @param $data
     * @return mixed
     */
    public function userAudit($data, $loginInfo)
    {
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
    public function delBatch(array $ids, $loginInfo)
    {
        $data['update_user_id'] = $loginInfo['id'];;
        $data['update_user_name'] = $loginInfo['username'];
        $data['is_delete'] = 1;
        return $this->order->delBatch($data, $ids);
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
    public function updateStatus($data, $loginInfo)
    {
        $data['update_user_id'] = $loginInfo['id'];;
        $data['update_user_name'] = $loginInfo['username'];
        return $this->user->updateStatus($data);
    }
    /*-----------------------------小程序端-------------------------------------*/
    /**
     * 小程序用户信息
     * @return mixed
     */
    public function getUserInfo($id)
    {
        return $this->user->getUserInfo($id);
    }

    /**
     * 创建订单
     * @param array $data
     * @param $userInfo
     * @return bool|mixed
     */
    public function createOrder(array $data, $userInfo)
    {
        //商品信息
        $goodsDetail = $this->goods->getApiGoodsDetailById($data["goods_id"]);
        //检测商品是否存在
        if ($goodsDetail["goods_status"] != 10 || $goodsDetail["is_delete"] != 0) {
            $this->setErrorMsg("商品已下架或不存在");
            return false;
        }
        //订单数据
        $data['user_id'] = $userInfo['id'];
        $data['user_name'] = $userInfo['user_name'];
        $data['phone'] = $userInfo['phone'];
        $data['order_no'] = $this->getOrderNo("HP");
        $data['total_price'] = $userInfo['user_name'];
        $data['total_price'] = $goodsDetail['book_price'];
        $data['goods_id'] = $goodsDetail['id'];
        $data['goods_price'] = $goodsDetail['good_price'];
        $data['score'] = $goodsDetail['score'];
        $data['buyer_remark'] = $data['buyer_remark'];
        $data['goods_name'] = $goodsDetail['goods_name'];
        $data['goods_cover'] = $goodsDetail['goods_cover'];
        return $this->order->createOrder($data);
    }

    /**
     * 订单列表
     * @param $userInfo
     * @param $page
     * @param $limit
     * @return mixed
     */
    public function getOrderLists($userInfo, $page, $limit)
    {
        $lists = $this->order->getOrderLists($userInfo, $page, $limit);
        foreach ($lists as &$item){
            $item['sales_actual'] = bcadd($item['sales_actual'],$item['sales_initial']);
        }
        return $this->getPageData($lists);
    }

    /**
     * 订单评价
     * @param $userInfo
     * @param $goods_id
     * @param $content
     * @param $pictures
     * @return bool
     */
    public function addComment($userInfo, $goods_id, $content, $pictures)
    {
        Log::info("【订单评价开始】-----当前用户信息：userInfo:" . json_encode($userInfo));
        //1.获取订单信息
        $orderInfo = Order::getOrderByNo($goods_id);
        if (empty($orderInfo)){
            Log::error('[订单评价]-----订单不存在');
            $this->setErrorMsg('订单不存在');
            return false;
        }
        //1.查询商品是否存在
        $goodsInfo = $this->goods->getApiGoodsDetail($orderInfo->goods_id);
        if(!$goodsInfo){
            Log::error('[订单评价]-----评价商品不存在');
            $this->setErrorMsg('商品不存在或已下架');
            return  false;
        }
        //评价数据
        $commentData = [
            'user_id' => $userInfo['id'],
            'user_name' => $userInfo['user_name'],
            'avatar_url' => $userInfo['avatar_url'],
            'goods_id' => $orderInfo->goods_id,
            'goods_name' => $goodsInfo->goods_name,
            'comment_content' => $content
        ];
        Log::info('【订单评价】--------评价数据commentData:' . json_encode($commentData));
        //开启事务
        DB::beginTransaction();
        try {
            //1.修改订单是否评价状态
            $this->order->updateOrder($userInfo['id']);
            //2.添加评论
            $comment = $this->comment->addComment($commentData);
            Log::info('【订单评价】-----评价成功返回数据：comment:'.json_encode($comment));
            //3.添加评价图片
            if(!empty($pictures)){
                $picData = [];
                foreach ($pictures as $key => $val) {
                    $picData[$key]['pic_id'] = $comment->id;
                    $picData[$key]['pic_type'] = 4;
                    $picData[$key]['pic_url'] = $val;
                }
                Log::info('【订单评价】------图片数据 pictures:'.json_encode($picData));
                $this->picture->addPicture($picData);
            }
            DB::commit();
            return true;
        } catch (\Exception $e) {
            Log::error('【订单评价】-------评价异常:e:'.json_encode($e));
            $this->setErrorMsg("系统异常，请稍后再试!");
            DB::rollBack();
            return  false;
        }
    }

}
