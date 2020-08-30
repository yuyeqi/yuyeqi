<?php

namespace App\Models;

use Carbon\Carbon;
use phpDocumentor\Reflection\Types\Self_;

/**
 * 角色模型
 * Class Role
 * @package App\Models
 */
class Role extends Base
{
    //定义模型关联表
    protected $table = 'role';
    //时间转换
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';

    /**
     * 角色详情
     * @param $id
     */
    public static function getDetail($id)
    {
        return self::where(['id'=>$id])->first();
    }

    /**
     * 角色列表
     * @param $limit
     * @return mixed
     */
    public function getList($limit)
    {
        return self::where(['is_delete' => 0])
            ->orderBy('id')
            ->paginate($limit);
    }

    /**
     * 修改角色
     * @param $data
     * @return mixed
     */
    public function updateRole($data){
        return self::where('id',$data['id'])->update($data);
    }

    /**
     * 角色列表
     * @return mixed
     */
    public function getRolesLists(){
        return self::select(['id','name'])->where(['is_delete'=>0])->get();
    }

}
