<?php

namespace App\Http\Controllers\Admin;

/**
 * 角色控制器
 * Class RoleController
 * @package App\Http\Controllers\Admin
 */
class RoleController extends BaseController

{
    /**
     * 角色列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function index(){
        return view('admin.role.index');
    }

    /**
     * 添加角色
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function add(){
        return view('admin.role.add');
    }

}
