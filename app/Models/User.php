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
    //字段类型转换
    protected $casts = [
        'id' => 'integer',
        'openid' => 'string',
        'session_key' => 'string',
        'nick_name' => 'string',
        'avatar_url' => 'string',
        'phone' => 'string',
        'user_name' => 'string',
        'sex' => 'integer',
        'position_name' => 'string',
        'org_name' => 'string',
        //'birthday' => 'datetime',
        'user_brand' => 'string',
        'province' => 'string',
        'city' => 'string',
        'area' => 'string',
        'address' => 'string',
        'delivery_id' => 'integer',
        'user_type' => 'integer',
        'parent_id' => 'integer',
        'parent_name' => 'string',
        'share_type' => 'integer',
        'audit_status' => 'integer',
        'audit_user_id' => 'integer',
        'audit_user_name' => 'string',
        'audit_remark' => 'string',
        'status' => 'integer'
    ];
    /**
     * 关联用户统计
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function userStatistic()
    {
        return $this->hasOne('App\Models\UserStatistic', 'user_id', 'id')
            ->select(['id', 'user_id', 'amount', 'withdraw_amount', 'frozen_amount', 'score', 'withdraw_score', 'present_score']);
    }

    /**
     * 推荐人
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function users()
    {
        return $this->hasOne('App\Models\User', 'parent_id', 'id')
            ->select(['id', 'user_name'])
            ->withDefault([
                'id' => 0,
                'user_name' => '平台'
            ]);
    }

    /*------------------------------------------后端---------------------------------------------------*/
    public function getLists($keywords, $userType, $status, $auditStatus, $limit)
    {
        //查询
        $field = ['id', 'openid', 'nick_name', 'avatar_url', 'phone', 'user_name', 'sex', 'position_name',
            'org_name', 'birthday', 'user_brand', 'province', 'city', 'area', 'address', 'user_type', 'parent_id',
            'audit_status', 'status', 'audit_user_id', 'audit_user_name', 'update_user_name', 'create_time',
            'update_time', 'parent_name'];
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
    public function updateStatus($data)
    {
        return self::where(['id' => $data['id']])->update($data);
    }
    /*---------------------------------------------小程序----------------------------------------------*/
    /**
     * 用户信息
     * @return mixed
     */
    public function getUserInfo($id)
    {
        $map = ['status' => 10, 'is_delete' => 0, 'id' => $id];
        $field = ['id', 'nick_name', 'avatar_url', 'phone', 'user_name', 'position_name', 'user_type', 'org_name', 'birthday', 'user_brand',
            'province', 'city', 'area', 'address', 'deliver_id', 'parent_name', 'share_type', 'audit_status', 'audit_user_name', 'status',
            'create_time', 'token'];
        return self::select($field)->where($map)->with('userStatistic')->first();
    }


    /**
     * 用户账户信息
     * @param $id
     * @return mixed
     */
    public function getUserAccount($id)
    {
        $map = ['status' => 10, 'is_delete' => 0, 'id' => $id, 'audit_status' => 2];
        $field = ['id', 'parent_name'];
        return self::select($field)->with('userStatistic')->where($map)->first();
    }

    /**
     * 更新用户默认地址
     * @param $userId
     * @param $addressId
     * @return mixed
     */
   public function updateUserAddress($userId, $addressId)
   {
       return self::where(['id' => $userId, 'is_delete' => 0])->update(['delivery_id' => $addressId]);
   }

    /**
     * 用户注册
     * @param $data
     * @return mixed
     */
    public function register($data)
    {
        return self::where(['id' => $data['id']])->update($data);
    }

    /**
     * 根据openid获取用户信息
     * @param $openid
     * @return mixed
     */
    public static function getUserInfoByOpenid($openid)
    {
        $field = ['id', 'openid', 'token', 'audit_status'];
        $map = ['is_delete' => 0, 'openid' => $openid];
        return self::select($field)->where($map)->first();
    }

    /**
     * 根据token获取用户信息
     * @param $token
     * @return mixed
     */
    public static function getUserBytoken($token)
    {
        $field = ['*'];
        // $field = ['id','openid','session_key','nick_name','avatar_url','phone','user_name','sex','audit_status','audit_status'];
        $map = ['is_delete' => 0, 'token' => $token];
        return self::select($field)->where($map)->first();
    }
}
