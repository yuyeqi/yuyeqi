<?php

namespace App\Http\Controllers\Admin;

use App\Http\Service\OrderService;
use App\Http\Service\UserService;
use App\Library\Render;
use App\Models\Order;
use App\Models\User;
use App\Models\UserStatistic;
use Illuminate\Http\Request;

/**
 * 订单控制器
 * Class OrderController
 * @package App\Http\Controllers\Admin
 */
class OrderController extends BaseController
{
    //订单服务层
    private $orderService;

    /**
     * OrderController constructor.
     */
    public function __construct()
    {
        parent:: __construct();
        $this->orderService = isset($this->orderService) ?: new OrderService();
    }

    /**
     * 订单列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){
        return view('admin.order.index');
    }
    /**
     * 订单列表
     * @param Request $request
     */
    public function getLists(Request $request){
        //接收参数
        $id = $request->get('id','');
        $keywords = $request->get('keywords','');
        $pay_status = intval($request->get('payStatus','0'));
        $startTime = intval($request->get('startTime','0'));
        $endTime = intval($request->get('endTime','0'));
        $limit = intval($request->get('limit','10'));
        $lists = $this->orderService->getLists($id,$keywords, $pay_status,$startTime,$endTime, $limit);
        return Render::table($lists->items(),$lists->total());
    }

    /**
     * 编辑
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editShow($id){
        $detail = Order::getOrderDetail($id);
        return view('admin.order.edit',['detail'=>$detail]);
    }

    /**
     * 修改
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Request $request){
        $data = $request->only(['id','update_price','score']);
        //修改数据
        try {
            $result = $this->orderService->edit($data, $this->loginInfo);
            if ($result){
                return Render::success('修改成功');
            }
            return Render::error($this->orderService->getErrorMsg() ?: "修改失败");
        } catch (\Exception $e) {
            return Render::error("系统异常，请稍后再试！");
        }

    }

    /**
     * 批量删除
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delBatch(Request $request){
        $ids = $request->input('ids');
        if (empty($ids)){
            return Render::error('参数错误');
        }
        //删除数据
        try {
            $result = $this->orderService->delBatch($ids, $this->loginInfo);
            if ($result > 0){
                return Render::success('删除成功');
            }
            return Render::error('删除失败');
        } catch (\Exception $e) {
            return Render::error("系统异常，请稍后再试！");
        }
    }

    /**
     * 查看
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id){
        $detail = Order::getOrderDetail($id);
        return view('admin.order.show',compact('detail'));
    }
}
