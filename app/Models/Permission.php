<?php

namespace App\Models;

use Carbon\Carbon;
use phpDocumentor\Reflection\Types\Self_;

/**
 * 权限模型
 * Class Permission
 * @package App\Models
 */
class Permission extends Base
{
    //定义模型关联表
    protected $table = 'permission';
    //时间转换
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';

    /**
     * 权限详情
     * @param $id
     */
    public static function getDetail($id)
    {
        return self::where(['is_delete'=>0,'id'=>$id])->with('permission')->first();
    }

    /**
     * 一级分类
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function first(){
        return $this->hasMany('App\Models\Permission','pid','id')
            ->select(['id', 'pid', 'name','type','permission_value','uri','icon'])
            ->where(['is_delete'=>0,'type'=>2]);
    }

    /**
     * 二级分类
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function second(){
        return $this->hasMany('App\Models\Permission','pid','id')
            ->select(['id', 'pid', 'name','type','permission_value','uri','icon'])
            ->where(['is_delete'=>0,'type'=>3]);
    }

    /**
     * 一级分类
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function permission(){
        return $this->belongsTo('App\Models\Permission','pid','id')
            ->select(['id','name']);
    }

    /**
     * 权限列表
     * @param $limit
     * @return mixed
     */
    public function getList($limit)
    {
        return self::where(['is_delete' => 0])
            ->orderBy('sort','asc')
            ->paginate($limit);
    }

    /**
     * 添加权限
     * @param $data
     * @return mixed
     */
    public function addPermission($data){
        return self::create($data);
    }

    /**
     * 修改
     * @param array $data
     * @return mixed
     */
    public function editPermission(array $data)
    {
        return self::where(["id"=>$data['id']])->update($data);
    }

    /**
     * 批量删除
     * @param $data
     * @param array $ids
     * @return mixed
     */
    public function delBatch($data, array $ids)
    {
        return self::whereIn('id',$ids)->update($data);
    }

    /**
     * 权限列表
     * @param string $keyword
     * @param int $limit
     * @return mixed
     */
    public function getLists(string $keyword, int $limit)
    {
        $map = ['is_delete'=>0];
        $field = ['*'];
        return self::select($field)
            ->when(!empty($keyword),function ($query) use ($keyword){
                return $query->where('name','like','%'.$keyword.'%');
            })
            ->where($map)
            ->with('permission')
            ->orderBy('sort')
            ->paginate($limit);
    }

    /**
     * 获取下拉选择分类
     * @return mixed
     */
    public function getSelectPermession()
    {
        $fields = ['id', 'pid', 'name'];
        $map = ['is_delete' => 0, 'type' => 1];
        return self::select($fields)
            ->where($map)
            ->with('first')
            ->get();
    }

    /**
     * 修改
     * @param array $data
     * @return mixed
     */
    public function edit(array $data)
    {
        return self::where(["id"=>$data['id']])->update($data);
    }

    /**
     * 权限列表
     * @return mixed
     */
    public function getPermissionLists(){
        $fields = ['id', 'pid', 'name'];
        $map = ['is_delete' => 0, 'type' => 1];
        return self::select($fields)
            ->where($map)
            ->with(['first.second'])
            ->get();
    }

    /**
     * 用户权限
     * @param $permissionids
     * @return mixed
     */
    public function getPermission($permissionids)
    {
        $fields = ['id', 'pid', 'name','type','permission_value','uri','icon'];
        $map = ['is_delete' => 0,'type'=>1];
        return self::select($fields)
            ->where($map)
            ->whereIn('id', $permissionids)
            ->with('first')
            ->orderBy('sort')
            ->get();
    }
}
