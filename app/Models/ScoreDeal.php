<?php


namespace App\Models;


/**
 *积分记录表
 * Class Picture
 * @package App\Models
 */
class ScoreDeal extends Base
{
    //定义模型关联表
    protected $table = 'hp_score_deal';

    //时间转换
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'udpate_time';
    //设置保存字段
    protected $guarded = [];

    /**
     * 交易积分列表
     * @param $userInfo
     * @param string $field
     * @param $dealType
     * @param $page
     * @param $limit
     * @return mixed
     */
    public static function getScoreList($userInfo, $field="*", $dealType, $page, $limit){
        $map = ['user_id'=>$userInfo['id'],'is_delete'=>0];
        $dealType > 0 && $map['deal_type'] = $dealType;
        return self::select($field)
            ->where($map)
            ->orderBy('id','desc')
            ->paginate($limit);
    }

    /**
     * 积分列表
     * @param $goodsId
     * @param $keywords
     * @param $page
     * @param $limit
     * @return mixed
     */
    public function scoreDealList($userId,$keywords,$dealType,$page,$limit){
        $map = ['is_delete'=>0];
        $userId > 0 && $map['user_id'] = $userId;
        $dealType >  0 && $map['deal_type'] = $dealType;
        $field = ['*'];
        return self::select($field)
            ->where($map)
            ->when(!empty($keywords),function ($query) use ($keywords){
                return $query->where('user_name','like','%'.$keywords.'%');
            })
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
