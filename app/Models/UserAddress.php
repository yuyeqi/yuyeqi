<?php


namespace App\Models;


/**
 *用户地址模型
 * Class Picture
 * @package App\Models
 */
class UserAddress extends Base
{
    //定义模型关联表
    protected $table = 'user_address';

    const UPDATED_AT = null;
    //设置保存字段
    protected $guarded = [];

    /**
     * 修改地址
     * @param $data
     * @return mixed
     */
    public function updateUserAddress($data){
        return self::where(['id'=>$data['id']])->update($data);
    }

    /**
     * 删除用户地址
     * @param $id
     * @return mixed
     */
    public function delUserAddress($id){
        return self::where(['id'=>$id])->update(['delete_status'=>1]);
    }

    /**
     * 用户地址列表
     * @param $userInfo
     * @param $page
     * @param $limit
     * @return mixed
     */
    public function getApiUserAddressLists($userInfo, $page, $limit){
        $field = ['id','consignee','phone','province','city','area','address','default_status'];
        $map = ['user_id'=>$userInfo['id'],'delete_status'=>0];
        return self::select($field)
            ->where($map)
            ->orderBy('create_time','desc')
            ->paginate($limit);
    }

    /**
     * 设置默认地址
     * @param $id
     * @return mixed
     */
    public function setDefaultUserAddress($id){
        return self::where(['id'=>$id,'delete_status'=>0])->update(['default_status'=>1]);
    }

    /**
     * 恢复用户的默认地址
     * @param $userId
     * @return mixed
     */
    public function rebackDefaultUserAddress($userId){
        return self::where(['user_id'=>$userId,'delete_status'=>0])->update(['default_status'=>0]);
    }

    /**
     * 获取默认地址
     * @param $id
     * @return mixed
     */
    public static function getDefaultUserAddress($id)
    {
        $map = ['user_id'=>$id,'default_status'=>1,'delete_status'=>0];
        return self::select(['id'])->where($map)->get();
    }
}
