<?php

namespace App\Models;

use phpDocumentor\Reflection\Types\Self_;

/**
 * 用户数据统计模型
 * Class UserStatistic
 * @package App\Models
 */
class UserStatistic extends Base
{
    //定义模型关联表
    protected $table = 'hp_user_statistic';

    /**
     * 用户账户列表
     * @param $keywords
     * @param $sort
     * @param $limit
     */
    public function getLists($id,s$keywords,$sort, $limit)
    {
        $map = ['is_delete'=>0];
        $id > 0 && $map['id'] = $id;
        $lists = self::where($map)
            ->when(!empty($keyword), function ($query) use ($keywords) {
                return $query->where('nick_name', 'like', '%' . $keywords . '%')
                    ->orWhere('phone', 'like', '%' . $keywords . '%')
                    ->orWhere('user_name', 'like', '%' . $keywords . '%');
            })
            ->orderBy($sort)
            ->paginate($limit);
    }

    /**
     * 更新账户信息
     * @param $data
     * @return mixed
     */
    public function updateAccout($data){
        return self::where(['user_id'=>$data['id']])->update($data);
    }

    /**
     * 账户详情
     * @param $id
     * @return mixed
     */
    public static function getAccountDetail($id){
        $map = ['status'=>10,'user_id'=>$id];
        return self::where($map)->first();
    }

    /**
     * 根据用户id,更新统计数量
     * @param $userId
     * @param $field
     * @return mixed
     */
    public function updateUserCount($userId,$field){
        return self::where(['user_id'=>$userId])->increment($field);
    }

    /**
     * 兑换商品更新用户账户信息
     * @param $data
     * @return mixed
     */
    public function updateExchangeNum($data){
        return self::where(['user_id'=>$data['id']])->update($data);
    }
}
