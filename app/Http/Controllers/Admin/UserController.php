<?php

namespace App\Http\Controllers\Admin;

use App\Http\Service\UserService;
use App\Library\Render;
use App\Models\User;
use App\Models\UserStatistic;
use Illuminate\Http\Request;

/**
 * 用户控制器
 * Class UserController
 * @package App\Http\Controllers\Admin
 */
class UserController extends BaseController
{
    //用户服务层
    private $userService;

    /**
     * UserController constructor.
     */
    public function __construct()
    {
        parent:: __construct();
        $this->userService = isset($this->userService) ?: new UserService();
    }

    /**
     * 用户列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){
        return view('admin.user.index');
    }
    /**
     * 用户列表
     * @param Request $request
     */
    public function getLists(Request $request){
        //接收参数
        $keywords = $request->get('keywords','');
        $userType = intval($request->get('user_type','0'));
        $status = intval($request->get('status','0'));
        $auditStatus = intval($request->get('auditStatus','0'));
        $limit = intval($request->get('limit','10'));
        $lists = $this->userService->getLists($keywords,$userType,$status,$auditStatus,$limit);
        return Render::table($lists->items(),$lists->total());
    }

    /**
     * 编辑
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editShow($id){
        $detail = $this->userService->getUserDetail($id);
        return view('admin.user.edit',['detail'=>$detail]);
    }

    /**
     * 修改
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Request $request){
        $data = $request->only(['id','phone','user_name','sex','org_name','birthday','user_type','position_name',
            'birthday','province','city','area','address','user_brand']);
        //修改数据
        try {
            $result = $this->userService->edit($data, $this->loginInfo);
            if ($result){
                return Render::success('修改成功');
            }
            return Render::error('修改失败');
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
            $result = $this->userService->delBatch($ids, $this->loginInfo);
            if ($result > 0){
                return Render::success('删除成功');
            }
            return Render::error('删除失败');
        } catch (\Exception $e) {
            return Render::error("系统异常，请稍后再试！");
        }
    }

    /**
     * 修改状态
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(Request $request){
        $data = $request->only(['id','status']);
        try {
            $result = $this->userService->updateStatus($data, $this->loginInfo);
            if ($result > 0){
                return  Render::success('操作成功');
            }
            return  Render::error('操作失败');
        } catch (\Exception $e) {
            return Render::error("系统异常，请稍后再试！");
        }
    }

    /**
     * 审核页面展示
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function auditShow($id){
        return view('admin.user.audit',compact("id"));
    }

    /**
     * 用户审核
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function audit(Request $request){
        $data = $request->only(['id','audit_status','audit_remark']);
            $result = $this->userService->userAudit($data, $this->loginInfo);
            if ($result > 0){
                return  Render::success('操作成功');
            }
            return  Render::error($this->userService->getErrorMsg() ?: '操作失败');
    }

    /**
     * 查看
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id){
        $detail = User::getUserDetail($id);
        return view('admin.user.show',compact('detail'));
    }

    /**
     * 账户信息
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function account($id){
        $detail = UserStatistic::getAccountDetail($id);
        return view('admin.user.account',compact('detail'));
    }

    /**
     * 提现页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function withdraw(){
        return view('admin.user.withdraw');
    }
    /**
     * 提现列表
     * @param Request $request
     * @return mixed
     */
    public function withdrawList(Request $request){
        $page = $request->input('page',1);
        $limit = $request->input('limit',10);
        $userId = $request->input('userId',0);
        $keywords = $request->input('keywords','');
        $lists = $this->userService->getWithdrawList($userId,$keywords,$page,$limit);
        return  Render::table($lists->items(),$lists->total());
    }

    /**
     * 钱包记录
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function walletDeal(){
        return view('admin.user.walletDeal');
    }

    /**
     * 钱包列表
     * @param Request $request
     * @return mixed
     */
    public function walletDealList(Request $request){
        $page = $request->input('page',1);
        $limit = $request->input('limit',10);
        $userId = $request->input('userId',0);
        $keywords = $request->input('keywords','');
        $dealType = $request->input('dealType',0);
        $lists = $this->userService->walletDealList($userId,$keywords,$dealType,$page,$limit);
        return  Render::table($lists->items(),$lists->total());
    }

    /**
     * 积分记录
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function scoreDeal(){
        return view('admin.user.scoreDeal');
    }

    /**
     * 积分交易列表
     * @param Request $request
     * @return mixed
     */
    public function scoreDealList(Request $request){
        $page = $request->input('page',1);
        $limit = $request->input('limit',10);
        $userId = $request->input('userId',0);
        $keywords = $request->input('keywords','');
        $dealType = $request->input('dealType',0);
        $lists = $this->userService->scoreDealList($userId,$keywords,$dealType,$page,$limit);
        return  Render::table($lists->items(),$lists->total());
    }

    /**
     * 推广人
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function promoter(){
        return view('admin.user.promoter');
    }

    /**
     * 积分交易列表
     * @param Request $request
     * @return mixed
     */
    public function promoterlList(Request $request){
        $page = $request->input('page',1);
        $limit = $request->input('limit',10);
        $userId = $request->input('userId',0);
        $keywords = $request->input('keywords','');
        $dealType = $request->input('dealType',0);
        $lists = $this->userService->promoterlList($userId,$keywords,$dealType,$page,$limit);
        return  Render::table($lists->items(),$lists->total());
    }

    /**
     * 批量删除提现记录
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delBatchWithdraw(Request $request){
        $ids = $request->input('ids');
        if (empty($ids)){
            return Render::error('参数错误');
        }
        //删除数据
        try {
            $result = $this->userService->delBatchWithdraw($ids, $this->loginInfo);
            if ($result > 0){
                return Render::success('删除成功');
            }
            return Render::error('删除失败');
        } catch (\Exception $e) {
            return Render::error("系统异常，请稍后再试！");
        }
    }

    /**
     * 批量删除钱包交易记录
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delBatchWallet(Request $request){
        $ids = $request->input('ids');
        if (empty($ids)){
            return Render::error('参数错误');
        }
        //删除数据
        try {
            $result = $this->userService->delBatchWallet($ids, $this->loginInfo);
            if ($result > 0){
                return Render::success('删除成功');
            }
            return Render::error('删除失败');
        } catch (\Exception $e) {
            return Render::error("系统异常，请稍后再试！");
        }
    }

    /**
     * 批量删除积分记录
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delBatchScore(Request $request){
        $ids = $request->input('ids');
        if (empty($ids)){
            return Render::error('参数错误');
        }
        //删除数据
        try {
            $result = $this->userService->delBatchScore($ids, $this->loginInfo);
            if ($result > 0){
                return Render::success('删除成功');
            }
            return Render::error('删除失败');
        } catch (\Exception $e) {
            return Render::error("系统异常，请稍后再试！");
        }
    }

    /**
     * 批量删除推广人
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delBatchPromoter(Request $request){
        $ids = $request->input('ids');
        if (empty($ids)){
            return Render::error('参数错误');
        }
        //删除数据
        try {
            $result = $this->userService->delBatchPromoter($ids, $this->loginInfo);
            if ($result > 0){
                return Render::success('删除成功');
            }
            return Render::error('删除失败');
        } catch (\Exception $e) {
            return Render::error("系统异常，请稍后再试！");
        }
    }

    /**
     * 体现审核
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function cush($id){
        return view('admin.user.cush',compact('id'));
    }

    /**
     * 提现审核
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function cushAudit(Request $request){
        $data = $request->only(['id','status','remark']);
        if (!$data['id']){
            return  Render::error('参数错误');
        }
        $result = $this->userService->cushAudit($data, $this->loginInfo);
        if ($result > 0){
            return  Render::success('审核通过');
        }
        return  Render::error($this->userService->getErrorMsg() ?: '操作失败');
    }
}
