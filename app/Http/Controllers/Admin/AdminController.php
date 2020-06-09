<?php

namespace App\Http\Controllers\Admin;

use App\Http\Service\AdminService;
use App\Library\Render;
use App\Models\Admin;
use Illuminate\Http\Request;

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
    public function __construct(){
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
        $status = intval($request->get('status','0'));
        $limit = intval($request->get('limit','10'));
        //获取数据
        $lists = $this->adminService->getAdminLists($keyword,$status,$limit);
        return Render::table($lists->items(),$lists->total());
    }
    /**
     * 展示登陆页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function showLogin(){
        return view('admin.admin.login');
    }
    /**
     * 用户登陆
     * @param $account  账号
     * @param $password 密码
     */
    public function login($account,$password){

    }
}
