<?php

namespace App\Http\Controllers\Admin;

use App\Http\Service\PermissionService;
use App\Http\Service\RoleService;
use App\Library\Render;
use App\Models\Permission;
use Illuminate\Http\Request;

/**
 * 权限控制器
 * Class PermissionController
 * @package App\Http\Controllers\Admin
 */
class PermissionController extends BaseController
{
    //角色服务
    private $permissionService;
    //权限服务层
    private $roleService;
    /**
     * PermissionController constructor.
     */

    public function __construct()
    {
        parent:: __construct();
        $this->permissionService = isset($this->permissionService) ?: new PermissionService();
        $this->roleService = isset($this->roleService) ?: new RoleService();
    }

    /**
     * 权限列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function index(){
        return view('admin.permission.index');
    }

    /**
     * 权限列表
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLists(Request $request){
        //接收参数
        $keyword = trim($request->get('keywords',''));
        $limit = intval($request->get('limit','10'));
        $lists = $this->permissionService->getLists($keyword,$limit);
        return Render::table($lists->items(),$lists->total());
    }
    /**
     * 添加权限
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function addShow(){
        $permission = $this->permissionService->getSelectLists();
        return view('admin.permission.add',compact('permission'));
    }

    /**
     * 添加权限
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request){
        $data = $request->only(['pid','name','icon','permission_value','type','uri','sort']);
        //添加数据
        try {
            $result = $this->permissionService->addPermission($data, $this->loginInfo);
            if (!empty($result)){
                return Render::success('添加成功');
            }
            return Render::error('添加失败');
        } catch (\Exception $e) {
            return Render::error($e->getMessage());
        }
    }

    /**
     * 编辑权限
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function editShow($id){
        $permission = $this->permissionService->getSelectLists();
        $detail = Permission::getDetail($id);
        return view('admin.permission.edit',compact('permission','detail'));
    }

    /**
     * 修改权限
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Request $request){
        $data = $request->only(['id','pid','icon','name','permission_value','type','uri','sort']);
        //修改数据
        try {
            $result = $this->permissionService->edit($data, $this->loginInfo);
            if ($result > 0){
                return Render::success('修改成功');
            }
            return Render::error('修改失败');
        } catch (\Exception $e) {
            return Render::error($e->getMessage());
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
            $result = $this->permissionService->delBatch($ids, $this->loginInfo);
            if ($result > 0){
                return Render::success('删除成功');
            }
            return Render::error('删除失败');
        } catch (\Exception $e) {
            return Render::error($e->getMessage());
        }
    }
}
