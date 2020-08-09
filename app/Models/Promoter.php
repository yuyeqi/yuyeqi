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
    protected $table = 'promoter as p';
    const UPDATED_AT = null;
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
            ->join('user as u', 'promoter_id', '=', 'u.id')
            ->orderBy('p.create_time','desc')
            ->paginate($limit);
    }


    /**
     * 推广列表
     * @param $goodsId
     * @param $keywords
     * @param $page
     * @param $limit
     * @return mixed
     */
    public function promoterlList($userId,$keywords,$dealType,$page,$limit){
        $map = ['is_delete'=>0];
        $userId > 0 && $map['promoter_id'] = $userId;
        $dealType >  0 && $map['share_type'] = $dealType;
        $field = ['*'];
        return self::select($field)
            ->when(!empty($keywords),function ($query) use ($keywords){
                return $query->where('promoter_user','like','%'.$keywords.'%')
                    ->orWhere('promoter_user_name','like','%'.$keywords.'%');
            })
            ->where($map)
            ->orderBy('create_time','desc')
            ->paginate($limit);
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
}
