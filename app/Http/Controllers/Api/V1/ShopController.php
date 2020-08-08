<?php


namespace App\Http\Controllers\Api\V1;


use App\Http\Service\GoodsCateService;
use App\Http\Service\GoodsService;
use App\Http\Service\OrderService;
use App\Http\Service\UserService;
use App\Library\Render;
use App\Models\Order;
use App\Models\ScoreDeal;
use App\Models\UserStatistic;
use EasyWeChat\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * 商城API控制器
 * Class PersonController
 * @package App\Http\Controllers\Api
 */
class ShopController extends BaseController
{
    private $goodsService;   //商品服务层
    private $goodsCateService; //商品分类服务层
    private $orderSerice;   //订单服务层
    private $app;   //微信支付
    private $userService;  //用户

    /**
     * ShopController constructor.
     */
    public function __construct()
    {
        parent:: __construct();
        $config = config('wechat.payment.default');
        $this->goodsService = isset($this->goodsService) ?: new GoodsService();
        $this->goodsCateService = isset($this->goodsCateService) ?: new GoodsCateService();
        $this->orderSerice = isset($this->orderSerice) ?: new OrderService();
        $this->userService = isset($this->userService) ?: new UserService();
        $this->app = isset($this->app) ?: Factory::payment($config);
    }

    /**
     * API商城列表页
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLists(Request $request)
    {
        //接收参数
        $keywords = $request->input("keywords", ""); //关键字
        $cateId = $request->input("cateId", 0);
        $sort = $request->input("sort", "");
        $page = $request->input("page", 1);
        $limit = $request->input("limit", 10);
        $lists = $this->goodsService->getLists($keywords, $cateId, $sort, $page, $limit);
        return Render::success("获取成功", $lists);
    }

    /**
     * 商城分类列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCateLists(Request $request)
    {
        $lists = $this->goodsCateService->getApiCateLists();
        return Render::success("获取成功", $lists);
    }

    /**
     * 商品详情
     * @param $request
     * @return \Illuminate\Http\JsonResponse商品详情
     */
    public function getShopDetail(Request $request)
    {
        $id = $request->input('id', 0);
        if ($id <= 0) {
            return Render::error('参数错误，请稍后再试!');
        }
        $detail = $this->goodsService->getApiGoodsDetail($id);
        return Render::success("获取成功", $detail);
    }

    /**
     * 创建订单
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createOrder(Request $request)
    {
        $data = $request->only(['goods_id', 'buyer_remark']);
        try {
            if ($rst = $this->orderSerice->createOrder($data, $this->userInfo)) {
                $payment = $this->unifiedorder($rst);
                Log::info('【支付返回】------data=' . json_encode($payment));
                //修改前端冲突
                $paymentData = [
                    'appId' => $payment['appId'],
                    'nonceStr' => $payment['nonceStr'],
                    'packageValue' => $payment['package'],
                    'signType' => $payment['signType'],
                    'paySign' => $payment['paySign'],
                    'timeStamp' => $payment['timestamp']
                ];
                if (!$payment) {
                    return Render::error('签名错误');
                }
                $data = [
                    'order_no' => $rst->order_no,
                    'payment' => $paymentData
                ];
                return Render::success("创建成功", $data);
            }
            return Render::error($this->orderSerice->getErrorMsg() ?: "创建订单失败");
        } catch (\Exception $e) {
            Log::info('【创建订单】-----创建失败:e=' . $e->getMessage());
            return Render::error("下单失败");
        }
    }

    /**
     * 订单列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOrderLists(Request $request)
    {
        $page = $request->input('page', 1);
        $limit = $request->input('limit', 10);
        $lists = $this->orderSerice->getOrderLists($this->userInfo, $page, $limit);
        return Render::success("获取成功", $lists);
    }

    /**
     * 订单详情
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOrderDetail(Request $request)
    {
        $id = $request->input("id", 0);
        if (empty($id)) {
            return Render::error("参数错误，请重试!");
        }
        $detail = Order::getOrderByNo($id);
        return Render::success("获取成功", $detail);
    }

    /**
     * 订单评价
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function comment(Request $request)
    {
        $goods_id = $request->input('goods_id', 0);
        $content = $request->input('content');
        $pictures = $request->input('pictures', '');
        if ($goods_id <= 0) {
            Log::error('【订单评价】----参数错误，id:' . $goods_id);
            return Render::error("参数错误，请重试");
        }
        if ($this->orderSerice->addComment($this->userInfo, $goods_id, $content, $pictures)) {
            return Render::success("添加成功");
        }
        return Render::error($this->orderSerice->getErrorMsg() ?: '添加失败');
    }

    /**
     * 微信支付
     * @param $order
     * @param $user
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function unifiedorder($order)
    {
        //设置支付参数
        $data = [
            'body' => '黄派门窗-订单支付',
            'out_trade_no' => $order->order_no,
            //'total_fee' => $order->total_price,
            'total_fee' => '101',
            'trade_type' => 'JSAPI', // 请对应换成你的支付方式对应的值类型
            'openid' => $this->userInfo['openid'],
        ];
        $result = $this->app->order->unify($data);
        Log::info('【统一下单】-----返回数据：data=' . json_encode($result));
        //记录支付记录
        if ($result['return_code'] == 'SUCCESS' && $result['result_code'] == 'SUCCESS') {
            //
            Log::info('【支付成功】-----生成预支付单成功:data=' . json_encode($result));
            $result = $this->app->jssdk->sdkConfig($result['prepay_id']);//第二次前面
            Log::info('【支付jssdk]--------jssdk' . json_encode($result));
            return $result;
        } else {
            Log::error('微信支付签名错误');
            return false;
        }
    }

    /**
     * 评论列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCommentList(Request $request)
    {
        $page = $request->input('page', 1);
        $limit = $request->input('limit', 10);
        $goods_id = $request->input('goods_id', 0);
        if ($goods_id <= 0) {
            return Render::error('缺少商品id');
        }
        $lists = $this->goodsService->getCommentList($goods_id, $page, $limit);
        return Render::success('获取成功', $lists);
    }

    /**
     * 微信支付回调
     * @param Request $request
     * @throws \EasyWeChat\Kernel\Exceptions\Exception
     */
    public function notify(Request $request)
    {
        $response = $this->app->handlePaidNotify(function ($message, $fail) {
            Log::info('【支付回调信息】----message=' . json_encode($message));
            // 1.使用通知里的 "微信支付订单号" 或者 "商户订单号" 去自己的数据库找到订单
            $order = Order::getOrderByNo($message['out_trade_no']);
            Log::info('【订单信息】-----订单信息：order=' . json_encode($order));
            if (!$order || $order->pay_status == 20 || $order->pay_time) { // 如果订单不存在 或者 订单已经支付过了
                return true; // 告诉微信，我已经处理完了，订单没找到，别再通知我了
            }
            Log::info('【订单信息】----------order=' . json_encode($order->toArray()));
            ///////////// <- 建议在这里调用微信的【订单查询】接口查一下该笔订单的情况，确认是已经支付 /////////////
            //2.查询微信订单是否支付
            $orderRet = $this->app->order->queryByOutTradeNumber($message['out_trade_no']);
            Log::info('【查询微信订单】--------wxOrder=' . json_encode($orderRet));
            if ($orderRet['return_code'] !== 'SUCCESS' &&
                array_get($orderRet, 'result_code') !== 'SUCCESS'
                && array_get($orderRet, 'trade_state') !== 'SUCCESS') {
                return true;
            }
            //3.验证支付金额
            Log::info('【微信支付】===========支付金额:total_fee=' . $message['cash_fee']);
            $amount = bcdiv($message['cash_fee'], 100, 2);   //支付金额
            Log::info('【微信支付金额】======total_fee=' . $amount);
            /* if ($amount != $order->total_price){
                 Log::error('【微信支付回调】--------支付金额不等于订单金额，支付失败');
                 return  true;
             }*/
            //支付成功处理
            if ($message['return_code'] === 'SUCCESS') { // return_code 表示通信状态，不代表支付状态
                // 1用户是否支付成功
                if (array_get($message, 'result_code') === 'SUCCESS') {
                    Log::info('【微信支付回调】=========修改订单状态');
                    //1.修改订单状态
                    $order->pay_time = date("Y-m-d h:i:s", time()); // 更新支付时间为当前时间
                    $order->pay_status = 20;
                    $order->transaction_id = $message['transaction_id'];
                    $order->pay_price = $amount;
                    //2.获取用户账户信息
                    $userStatistic = UserStatistic::getAccountDetail($order->user_id);
                    Log::info('【用户信息】===========userInfo='.json_encode($userStatistic));
                    if (!$userStatistic) {
                        Log::error('用户信息不存在');
                        return true;
                    }
                    Log::info('【用户信息】=====用户id='.$order->user_id);
                    //2.赠送用户积分
                    $scoreData = [
                        $data['order_num'] = bcadd($userStatistic->order_num, 1),
                        $data['score'] = bcadd($userStatistic->score, $order->score),
                        $data['user_id'] => $order->user_id
                    ];
                    //3.用户账户信息
                    $this->userService->updateUserData($scoreData);
                    //4.记录赠送积分记录
                    $scoreLog = [
                        'deal_no' => $order->order_no,
                        'user_id' => $order->user_id,
                        'user_name' => $userStatistic->user_name,
                        'deal_score' => $order->score,
                        'surplus_score' => $data['score'],
                        'deal_type' => 3,
                        'remark' => '订单赠送积分'
                    ];
                    ScoreDeal::create($scoreLog);
                } elseif (array_get($message, 'result_code') === 'FAIL') {
                    $order->pay_status = '10';
                }
            } else {
                return $fail('通信失败，请稍后再通知我');
            }
            $order->save(); // 保存订单
            Log::info('【订单支付】======支付成功');
            return true; // 返回处理完成
        });

        $response->send(); // return $response;
    }
}
