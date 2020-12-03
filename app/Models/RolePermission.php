<?php

namespace App\Models;

use Carbon\Carbon;

/**
 * 角色权限模型
 * Class RolePermission
 * @package App\Models
 */
class RolePermission extends Base
{
    //定义模型关联表
    protected $table = 'role_permission';

    public $timestamps = false;

    /**
     * 角色权限
     * @param $id
     * @return mixed
     */
    public function getRolePermission($id){
        return self::where('role_id',$id)->get();
    }

    /**
     * 删除角色权限
     * @param $roleId
     * @return mixed
     */
    public function deletePermission($roleId){
        return self::where(['role_id'=>$roleId])->delete();
    }

    /**
     * 获取权限ids
     * @param $roles
     * @return mixed
     */
    public function getPermissionIds($roles)
    {
        return self::whereIn('role_id',$roles)->pluck('permission_id');
    }

}
