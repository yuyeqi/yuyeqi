<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PersonValidator;
use App\Http\Service\AdminService;
use App\Library\Render;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * 后台用户控制器
 */
class AdminController extends BaseController

{
    private $adminService;

    /**
     * 构造方法
     * AdminController constructor.
     * @param Admin $admin
     */
    public function __construct(Request $request){
        $this->middleware(function ($request, $next) {
            $this->loginInfo = $request->session()->get('admin');
            return $next($request);
        });
        $this->adminService = isset($this->adminService) ?: new AdminService();
    }

    /**
     * 后台用户列表页面展示
     */
    public function index(){
        return view('admin.admin.index');
    }

    /**
     * 请求后台列表数据
     * @param null $search
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAdminLists(Request $request){
        //接收参数
        $keyword = trim($request->get('keywords',''));
        $limit = intval($request->get('limit','10'));
        //获取数据
        $lists = $this->adminService->getAdminLists($keyword,$limit);
        return Render::table($lists->items(),$lists->total());
    }

    /**
     * 展示添加页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function addShow(){
        return view('admin.admin.add');
    }
    /**
     * 添加用户
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function add(PersonValidator $request){
        $data = $request->only('username','account','phone','password','sex','email','remark');
        //添加数据
        if ($this->adminService->addAdmin($data,$this->loginInfo)){
            return  Render::success('添加成功');
        }
        return Render::error('添加失败');
    }

    /**
     * 查看用户信息
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function showInfo($id){
        $detail = $this->adminService->getAdminDetail($id);
        return view('admin.admin.show',['detail'=>$detail]);
    }

    /**
     * 编辑
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function edit($id){
        $detail = $this->adminService->getAdminDetail($id);
        return view('admin.admin.edit',['detail'=>$detail]);
    }

    /**
     * 更新用户信息
     * @param PersonValidator $adminPost
     * @return \Illuminate\Http\JsonResponse
     */
    public function editPost(PersonValidator $adminPost, $id){
        $data = $adminPost->only('id','username','account','phone','password','sex','email','remark');
        //修改
        if ($this->adminService->updateAdmin($data,$this->loginInfo)){
            return  Render::success('修改成功');
        }
        return Render::error('修改失败');
    }

    /**
     * 设置密码
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePwd(Request $request){
        $data = $request->only('id','password');
        //数据验证
        $validator =Validator::make($data,[
            'id' => 'required|int',
            'password' => 'required|min:6|max:16',
        ],[
            'id' => '缺少必要参数',
            'password.required' => '密码不能为空',
            'password.min' => '密码长度必须是6-16位',
            'password.max' => '密码长度必须是6-16位'
        ]);
        if ($validator->fails()){
            return Render::error($validator->errors()->first());
        }
        //修改密码
        if($this->adminService->updatePwd($data,$this->loginInfo)){
            return Render::success('设置成功');
        }
        return Render::error('设置失败');
    }

    /**
     * 删除司机
     * @param $id
     * @return mixed
     */
    public function delete($id){
        if ($this->adminService->deleteAdmin($id,$this->loginInfo)){
            return  Render::success('删除成功');
        }
        return  Render::error('删除失败');
    }

    /**
     * 设置用户状态
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(Request $request){
        $data = $request->only('id','status');
        //数据验证
        $validator =Validator::make($data,[
            'id' => 'required|integer',
            'status' => 'required|integer',
        ]);
        //更新状态
        if ($this->adminService->updateStatus($data,$this->loginInfo)){
            return Render::success('设置成功');
        }
        return  Render::error('设置失败');
    }

    /**
     * 批量删除
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteAll(Request $request){
        $ids = $request->input('ids');
        if($this->adminService->deleteAll($ids,$this->loginInfo)){
            return Render::success('删除成功');
        }
        return  Render::error('删除失败');
    }
}
