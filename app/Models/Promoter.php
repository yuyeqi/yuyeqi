<?php


namespace App\Models;


/**
 *推广人模型
 * Class Picture
 * @package App\Models
 */
class Promoter extends Base
{
    //定义模型关联表
    protected $table = 'hp_promoter as p';

    //设置保存字段
    protected $guarded = [];

    /**
     * 关联用户
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user(){
        return $this->hasOne('App\Models\User','promoter_user_id','id')
            ->select(['id','avatar_url'])
            ->where(['is_delete'=>0]);
    }
    /**
     * 推广用户列表
     * @param $userInfo
     * @param $field
     * @param $page
     * @param $limit
     * @return mixed
     */
    public static function getPromoterLists($userInfo, $field, $page, $limit)
    {
        $map = ['promoter_id'=>$userInfo['id'],'p.is_delete'=>0];
        return self::select($field)
            ->where($map)
            ->join('hp_user as u', 'promoter_user_id', '=', 'u.id')
            ->orderBy('p.create_time','desc')
            ->paginate($limit);
    }


}
