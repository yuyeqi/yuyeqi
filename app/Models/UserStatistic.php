<?php

namespace App\Models;

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
    public static function getDetail($id){
        $map = ['is_delete'=>0,'id'=>$id];
        return self::where($map)->first();
    }
}
