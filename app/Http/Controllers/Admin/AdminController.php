<?php

namespace App\Http\Controllers\Admin;

use App\Http\Service\AdminService;
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
     * 后台用户列表
     * @param Request $request\
     */
    public function index($search = null){
        $lists = $this->adminService->getAdminLists($search);
        return view('admin.admin.index',['lists'=>$lists]);
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
