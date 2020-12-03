<?php


namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\AddressValidator;
use App\Http\Service\UserCateService;
use App\Http\Service\UserService;
use App\Library\Render;
use App\Models\Config;
use App\Models\UserStatistic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * 用户控制器
 * Class UserController
 * @package App\Http\Controllers\Api\V1
 */
class UserController extends BaseController
{
    private $userService;  //用户服务层
    private $userCateService;   //用户分类服务层

    /**
     * UserController constructor.
     */
    public function __construct()
    {
        parent:: __construct();
        $this->userService = isset($this->userService) ?: new UserService();
        $this->userCateService = isset($this->userCateService) ?: new UserCateService();
    }

    /**
     * 用户账户信息
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserAccount(){
        $wallet = $this->userService->getUserAccount($this->userInfo);
        return Render::success("获取成功",$wallet);
    }

    /**
     * 兑换现金
     * @return \Illuminate\Http\JsonResponse
     */
    public function exchangeCash(Request $request){
        //get请求获取页面数据
        if ($request->isMethod('get')){
            //积分兑现背景图和提示语
            $amount = UserStatistic::getAccountDetail($this->userInfo['id'],['id','amount']);
            $detail = Config::getConfigByNo('scoreToCash');
            return  Render::success('获取成功',compact('detail','amount'));
        }else{
            $score = $request->input("score",0);
            $remark = $request->input("remark",'');
            try {
                if ($this->userService->exchangeCash($score,$remark,$this->userInfo)) {
                    return Render::success("兑换成功");
                }
                return  Render::error($this->userService->getErrorMsg() ?: "兑换失败");
            } catch (\Exception $e) {
                return  Render::error("系统异常，请稍后再试！");
            }
        }

    }

    /**
     * 用户提现
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function withdraw(Request $request){
        //提现页面数据
        if ($request->isMethod('get')){
            //积分兑现背景图和提示语
            $detail = Config::getConfigByNo('withdrawCush');
            return  Render::success('获取成功',compact('detail'));
        }
        $cush = $request->input("cush",0);
        $remark = $request->input("remark",'');
        try {
            if ($this->userService->withdraw($cush,$remark,$this->userInfo)) {
                return Render::success("提现成功");
            }
            return  Render::error($this->userService->getErrorMsg() ?: "提现失败");
        } catch (\Exception $e) {
            dd($e);
            return  Render::error("系统异常，请稍后再试！");
        }
    }

    /**
     * 钱包详情
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getWalletList(Request $request){
        $page = $request->input("page",1);
        $limit = $request->input("limit",10);
        $dealType = $request->input('dealType',0);
        $lists = $this->userService->getWalletList($this->userInfo, $dealType, $page, $limit);
        return Render::success("获取成功",$lists);
    }

    /**
     * 提现记录
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCushLists(Request $request){
        $page = $request->input("page",1);
        $limit = $request->input("limit",10);
        $status = $request->input('status',0);
        $lists = $this->userService->getCushLists($this->userInfo, $status, $page, $limit);
        return Render::success("获取成功",$lists);
    }

    /**
     * 推广用户列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPromoterLists(Request $request){
        $page = $request->input("page",1);
        $limit = $request->input("limit",10);
        $lists = $this->userService->getPromoterLists($this->userInfo, $page, $limit);
        return Render::success("获取成功",$lists);
    }

    /**
     * 账户页面信息
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAccountInfo(){
        //账户信息
        $field = ['id','amount','withdraw_amount','frozen_amount'];
        $account = UserStatistic::getAccountDetail($this->userInfo['id'],$field);
        $background = Config::getConfigByNo('accountBg')->background;
        return Render::success('获取成功',compact('account','background'));
    }
    /**
     * 积分页面信息
     * @return \Illuminate\Http\JsonResponse
     */
    public function getScoreInfo(){
        //账户信息
        $field = ['id','score','withdraw_score','present_score'];
        $account = UserStatistic::getAccountDetail($this->userInfo['id'],$field);
        $background = Config::getConfigByNo('accountBg')->background;
        return Render::success('获取成功',compact('account','background'));
    }
    /**
     * 积分列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getScoreList(Request $request){
        $page = $request->input("page",1);
        $limit = $request->input("limit",10);
        $dealType = $request->input('dealType',0);
        $lists = $this->userService->getScoreList($this->userInfo, $dealType, $page, $limit);
        return Render::success("获取成功",$lists);
    }

    /**
     * 商品兑换记录
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getExRecordLists(Request $request){
        $page = $request->input("page",1);
        $limit = $request->input("limit",10);
        $lists = $this->userService->getExRecordLists($this->userInfo, $page, $limit);
        return Render::success("获取成功",$lists);
    }

    /**
     * 用户地址列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAddressLists(Request $request){
        $page = $request->input('page',1);
        $limit = $request->input('limit',10);
        $lists = $this->userService->getApiUserAddressLists($this->userInfo,$page,$limit);
        return Render::success("获取成功",$lists);
    }

    /**
     * 添加用户收获地址地址
     * @param AddressValidator $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addUserAddress(AddressValidator $request){
        $data = $request->only(['consignee','phone','province','city','area','address']);
        if ($this->userService->addUserAddress($this->userInfo,$data)){
            return Render::success('添加成功');
        }
        return  Render::error($this->userService->getErrorMsg() ?: '添加失败');
    }

    /**
     * 修改用户地址
     * @param AddressValidator $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function editUserAddress(AddressValidator $request){
        $data = $request->only(['id','consignee','phone','province','city','area','address']);
        //检测是否存在地址id
        if (empty($data['id'])){
            Log::error('【修改地址】-----参数错误，id:'.$data['id']);
            return  Render::error("参数错误，请稍后再试!");
        }
        if ($this->userService->editUserAddress($this->userInfo,$data)){
            return Render::success('修改成功');
        }
        return  Render::error($this->userService->getErrorMsg() ?: '修改失败');
    }

    /**
     * 删除收货地址
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delUserAddress(Request $request){
        $id = $request->input('id',0);
        //检测是否存在地址id
        if ($id <= 0){
            Log::error('【修改地址】-----参数错误，id:'.$id);
            return  Render::error("参数错误，请稍后再试!");
        }
        try {
            if ($this->userService->delUserAddress($id)) {
                Log::info('【用户地址删除】----用户信息：userInfo=' . json_encode($this->userInfo) . ',用户地址id=' . $id);
                return Render::success('删除成功');
            }
            Log::error('【用户地址删除】----错误信息=' . $this->userService->getErrorMsg());
            return Render::error($this->userService->getErrorMsg() ?: '删除失败');
        } catch (\Exception $e) {
            Log::error('【用户地址删除】----错误信息: e=' . $e->getErrorMsg());
            return Render::error('系统异常，请稍后再试！');
        }
    }

    /**
     * 设置默认收货地址
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setDefaultUserAddress(Request $request){
        $id = $request->input('id',0);
        //检测是否存在地址id
        if ($id <= 0){
            Log::error('【修改地址】-----参数错误，id:'.$id);
            return  Render::error("参数错误，请稍后再试!");
        }
        if ($this->userService->setDefaultUserAddress($this->userInfo,$id)){
            return Render::success('设置成功');
        }
        return  Render::error($this->userService->getErrorMsg() ?: '设置失败');
    }
}
