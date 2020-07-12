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
            dd($e->getMessage());
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
        try {
            $result = $this->userService->userAudit($data, $this->loginInfo);
            if ($result > 0){
                return  Render::success('操作成功');
            }
            return  Render::error('操作失败');
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
        $detail = User::getUserDetail($id);
        return view('admin.user.show',compact('detail'));
    }

    /**
     * 账户信息
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function account($id){
        $detail = UserStatistic::getDetail($id);
        return view('admin.user.account',compact('detail'));
    }
}
