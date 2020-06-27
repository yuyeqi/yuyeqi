<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Config;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * 后台用户模型
 * Class Admin
 * @package App\Models
 */
class Admin extends Authenticatable implements JWTSubject
{
    use Notifiable;
    //定义模型关联表
    protected $table = 'hp_admin';
    //时间转换
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';
    //时间格式
    protected $dateFormat = 'U';

    /**
     * 创建时间
     * @return false|string
     */
    public function getCreateTimeAttribute(){
        return date('Y-m-d H:i:s', $this->attributes['create_time']);
    }

    /**
     * 更新时间
     * @return false|string
     */
    public function getUpdateTimeAttribute(){
        return date('Y-m-d H:i:s', $this->attributes['update_time']);
    }

    //隐藏字段
    protected $hidden = ['password','is_delete'];

    //登录时间获取器
    public function getLoginTimeAttribute(){
        return date('Y-m-d H:i:s', $this->attributes['login_time']);
    }

    //设置保存字段
    protected $guarded = [

    ];

    //后台用户列表
    public function getAdminLists($keyword,$limit){
        //查询
        $field = ['id','username','phone','sex','account','status','is_login','update_user_name',
            'create_user_name','login_time','update_time','create_time'];
        $lists = self::where(['is_delete'=>0])
            ->when(!empty($keyword),function ($query) use ($keyword){
                return $query->where('username','like','%'.$keyword.'%')
                    ->orWhere('phone','like','%'.$keyword.'%')
                    ->orWhere('account','like','%'.$keyword.'%');
            })
            ->whereIn('status',[0,1])
            ->select($field)
            ->orderBy('id','desc')
            ->paginate($limit);
        return $lists;
    }

    /**
     * 添加用户
     * @param $data
     * @return bool
     */
    public function addAdmin($data){
        return self::create($data);
    }

    /**
     * 后台用户详情
     * @param $id
     * @return mixed
     */
    public function getAdminDetail($id){
        $map = ['id'=>$id,'is_delete'=>Config::get('constants.IS_DELETE')];
        return self::where($map)->first();
    }

    /**
     * 修改用户
     * @param $data
     * @return bool
     */
    public function updateAdmin($data){
        return self::where(["id"=>$data['id']])->update($data);
    }

    /**
     * 批量更新
     * @param $ids
     * @param $data
     * @return mixed
     */
    public function deleteAll($ids,$data){
        return self::whereIn('id',$ids)->update($data);
    }

    /**
     * @inheritDoc
     */
    public function getJWTIdentifier()
    {
        // TODO: Implement getJWTIdentifier() method.
        return $this->getKey();
    }

    /**
     * @inheritDoc
     */
    public function getJWTCustomClaims()
    {
        // TODO: Implement getJWTCustomClaims() method.
        return [];
    }

    /**
     * 根据账号获取用户信息
     * @param $account
     */
    public function getAdminByAcount($account)
    {
        $map = ['account'=>$account,'status'=>0,'is_delete'=>0];
        return self::select(['id'])->where($map)->first();
    }
}
