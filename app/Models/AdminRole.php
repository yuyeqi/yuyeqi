<?php

namespace App\Models;

use Carbon\Carbon;

/**
 * 用户角色模型
 * Class AdminRole
 * @package App\Models
 */
class AdminRole extends Base
{
    //定义模型关联表
    protected $table = 'admin_role';

    public $timestamps = false;

    /**
     *用户角色
     * @param $adminId
     * @return mixed
     */
    public function getAdminRoles($adminId){
        return self::where('admin_id',$adminId)->get();
    }

    /**
     * 删除角色
     * @param $adminId
     * @return mixed
     */
    public function deletAminRole($adminId){
        return self::where('admin_id',$adminId)->delete();
    }

    /**
     * 用户角色列表
     * @param $adminId
     * @return mixed
     */
    public function getMeanLists($adminId){
        //1.获取所有的角色
        $roles = self::where('admin_id',$adminId)->pluck('role_id');
        //2.获取所有的权限ids
        $permissionids = (new RolePermission())->getPermissionIds($roles);
        //3.获取所有的权限
        $permission = (new Permission())->getPermission($permissionids);
        foreach ($permission as $item){
            if (isset($item['first'])){
                foreach ($item['first'] as $val){
                    if (!in_array($val['id'],$permissionids->toArray())){
                        unset($val);
                    }
                }
            }
        }
        return $permission;
    }

}
