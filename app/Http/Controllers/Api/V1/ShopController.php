<?php


namespace App\Http\Controllers\Api\V1;


use App\Http\Service\GoodsCateService;
use App\Http\Service\GoodsService;
use App\Http\Service\OrderService;
use App\Library\Render;
use App\Models\Order;
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
                if (!$payment){
                    return  Render::error('签名错误');
                }
                $data = [
                    'order_no' => $rst->order_no,
                    'payment' => $payment
                ];
                return Render::success("创建成功", $data);
            }
            return Render::error($this->orderSerice->getErrorMsg() ?: "创建订单失败");
        } catch (\Exception $e) {
            return Render::error("创建失败");
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
        if ($id <= 0) {
            return Render::error("参数错误，请重试!");
        }
        $detail = Order::getOrderDetail($id);
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
    public function unifiedorder($order)
    {
        //设置支付参数
        $data = [
            'body' => '黄派门窗-订单支付',
            'out_trade_no' => $order->id,
            'total_fee' => $order->total_price,
            'notify_url' => 'https://pay.weixin.qq.com/wxpay/pay.action', // 支付结果通知网址，如果不设置则会使用配置里的默认地址
            'trade_type' => 'JSAPI', // 请对应换成你的支付方式对应的值类型
            'openid' => $this->userInfo['openid'],
        ];
        $result = $this->app->order->unify($data);
        //记录支付记录
        if ($result['return_code'] == 'SUCCESS' && $result['result_code'] == 'SUCCESS') {
            //
            $result = $this->app->jssdk->appConfig($result['prepay_id']);//第二次前面
            return $result;
        }else{
            Log::error('微信支付签名错误');
            return false;
        }
    }

    /**
     * 评论列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCommentList(Request $request){
        $page = $request->input('page',1);
        $limit = $request->input('limit',10);
        $goods_id  = $request->input('goods_id',0);
        if ($goods_id <= 0){
            return  Render::error('缺少商品id');
        }
        $lists = $this->goodsService->getCommentList($goods_id,$page,$limit);
        return Render::success('获取成功',$lists);
    }
}
