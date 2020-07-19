<?php

namespace App\Models;


use phpDocumentor\Reflection\Types\Self_;

/**
 * 用户模型
 * Class User
 * @package App\Models
 */
class User extends Base
{    //定义模型关联表
    protected $table = 'hp_user';
    //时间转换
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';

    //性别
    public function getSexAttribute($value)
    {
        $data = [
            '0' => ['status' => 0, 'status_name' => '未知'],
            '1' => ['status' => 1, 'status_name' => '男'],
            '2' => ['status' => 2, 'status_name' => '女']
        ];
        return $data[$value];
    }

    //用户类型
    public function getUserTypeAttribute($value)
    {
        $data = [
            '1' => ['status' => 1, 'status_name' => '设计师'],
            '2' => ['status' => 2, 'status_name' => '异业'],
            '3' => ['status' => 3, 'status_name' => '用户'],
            '4' => ['status' => 4, 'status_name' => '员工'],
            '5' => ['status' => 4, 'status_name' => '其他']
        ];
        return $data[$value];
    }

    //审核状态
    public function getAuditStatusAttribute($value)
    {
        $data = [
            '1' => ['status' => 1, 'status_name' => '审核中'],
            '2' => ['status' => 2, 'status_name' => '审核通过'],
            '3' => ['status' => 3, 'status_name' => '拒绝']
        ];
        return $data[$value];
    }

    //状态
    public function getStatusAttribute($value)
    {
        $data = [
            '10' => ['status' => 10, 'status_name' => '正常'],
            '20' => ['status' => 20, 'status_name' => '禁用']
        ];
        return $data[$value];
    }

    /**
     * 关联用户统计
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function userStatistic()
    {
        return $this->hasOne('App\Models\UserStatistic', 'user_id', 'id')
            ->select(['id', 'user_id', 'amount', 'withdraw_amount','frozen_amount', 'score', 'withdraw_score', 'frozen_score']);
    }

    /**
     * 推荐人
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function users()
    {
        return $this->hasOne('App\Models\User', 'parent_id', 'id')
            ->select(['id','user_name'])
            ->withDefault([
                'id' => 0,
                'user_name' => '平台'
            ]);
    }

    /*------------------------------------------后端---------------------------------------------------*/
    public function getLists($keywords, $userType, $status, $auditStatus, $limit)
    {
        //查询
        $field = ['id', 'open_id', 'nick_name', 'avatar_url', 'phone', 'user_name', 'sex', 'position_name',
            'org_name', 'birthday', 'user_brand', 'province', 'city', 'area', 'address', 'user_type', 'parent_id',
            'audit_status', 'status', 'audit_user_id', 'audit_user_name', 'update_user_name', 'create_time',
            'update_time'];
        //搜索条件
        $map = ['is_delete' => 0];
        $userType > 0 && $map['user_type'] = $userType;
        $status > 0 && $map['status'] = $status;
        $auditStatus > 0 && $map['audit_status'] = $auditStatus;
        $lists = self::where(['is_delete' => 0])
            ->when(!empty($keyword), function ($query) use ($keywords) {
                return $query->where('nick_name', 'like', '%' . $keywords . '%')
                    ->orWhere('phone', 'like', '%' . $keywords . '%')
                    ->orWhere('org_name', 'like', '%' . $keywords . '%')
                    ->orWhere('user_name', 'like', '%' . $keywords . '%');
            })
            ->where($map)
            ->select($field)
            ->orderBy('id', 'desc')
            ->paginate($limit);
        return $lists;
    }

    /**
     * 更新用户信息
     * @param $data
     * @return mixed
     */
    public function updateUserInfoById($data)
    {
        return self::where(['id' => $data['id']])->update($data);
    }

    /**
     * 审核用户
     * @param $data
     * @return mixed
     */
    public function auditUser($data)
    {
        return self::where(['id' => $data['id']])->update($data);
    }

    /**
     * 更新用户状态
     * @param $data
     * @return mixed
     */
    public function updateUserStatus($data)
    {
        return self::where(['id' => $data['id']])->update($data);
    }

    /**
     * 用户详情
     * @param $id
     * @return mixed
     */
    public static function getUserDetail($id)
    {
        $map = ['is_delete' => 0, 'id' => $id];
        return self::where($map)->with('users')->first();
    }

    /**
     * 批量删除
     * @param $data
     * @param array $ids
     * @return mixed
     */
    public function delBatch($data, array $ids)
    {
        return self::whereIn('id', $ids)->update($data);
    }

    /**
     * 更新状态
     * @param $data
     * @return mixed
     */
    public function updateStatus($data){
        return self::where(['id'=>$data['id']])->update($data);
    }
    /*---------------------------------------------小程序----------------------------------------------*/
    /**
     * 用户信息
     * @return mixed
     */
    public function getUserInfo($id)
    {
        $map = ['status' => 10, 'is_delete' => 0, 'id' => $id];
        $field = ['id', 'nick_name', 'avatar_url', 'user_type'];
        return self::select($field)->where($map)->with('userStatistic')->first();
    }


    /**
     * 用户账户信息
     * @param $id
     * @return mixed
     */
    public function getUserAccount($id)
    {
        $map = ['status' => 10, 'is_delete' => 0, 'id' => $id,'audit_status'=>2];
        $field = ['id', 'parent_name'];
        return self::select($field)->with('userStatistic')->where($map)->first();
    }

    /**
     * 更新用户默认地址
     * @param $userId
     * @param $addressId
     * @return mixed
     */
   public function updateUserAddress($userId,$addressId){
        return self::where(['id'=>$userId])->update(['deliver_id'=>$addressId]);
   }

}
