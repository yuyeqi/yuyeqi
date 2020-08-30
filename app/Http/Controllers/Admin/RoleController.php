<?php

namespace App\Http\Controllers\Admin;

use App\Http\Service\PermissionService;
use App\Http\Service\RoleService;
use App\Library\Render;
use App\Models\Role;
use Illuminate\Http\Request;

/**
 * 角色控制器
 * Class RoleController
 * @package App\Http\Controllers\Admin
 */
class RoleController extends BaseController
{

    //角色服务层
    private $roleService;
    //权限服务层
    private $permissionService;
    /**
     * PersonController constructor.
     */

    public function __construct()
    {
        parent:: __construct();
        $this->roleService = isset($this->roleService) ?: new RoleService();
        $this->permissionService = isset($this->permissionService) ?: new PermissionService();
    }

    /**
     * 角色列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function index(){
        return view('admin.role.index');
    }

    /**
     * 角色列表
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLists(Request $request){
        $limit = intval($request->get('limit','10'));
        $lists = $this->roleService->getLists($limit);
        return Render::table($lists->items(),$lists->total());
    }
    /**
     * 添加角色
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function addShow(){
        $permission = $this->permissionService->getPermissionLists();
        return view('admin.role.add',compact('permission'));
    }

    /**
     * 添加角色
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request){
        $data = $request->only(['name','ids','description']);
        $result = $this->roleService->addRole($data, $this->loginInfo);
        if ($result){
            return Render::success('添加成功');
        }
        return Render::error($this->roleService->getErrorMsg() ?: '添加失败');
    }

    /**
     * 编辑角色
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function editShow($id){
        //权限列表
        $permission = $this->permissionService->getPermissionLists();
        //角色详情
        $detail = Role::getDetail($id);
        //角色权限
        $roles = [];
        $rolePermission = $this->roleService->getRolePermission($id);
        if (!empty($rolePermission)){
            $roles = array_pluck($rolePermission,'permission_id');
        }
        return view('admin.role.edit',compact('permission','detail','roles'));
    }

    /**
     * 修改角色
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Request $request){
        $data = $request->only(['id','name','ids','description']);
        $result = $this->roleService->editRole($data, $this->loginInfo);
        if ($result){
            return Render::success('修改成功');
        }
        return Render::error($this->roleService->getErrorMsg() ?: '修改失败');
    }

}
