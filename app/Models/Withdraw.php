<?php


namespace App\Models;


/**
 *用户提现模型
 * Class Picture
 * @package App\Models
 */
class Withdraw extends Base
{
    //定义模型关联表
    protected $table = 'withdraw';

    //设置保存字段
    protected $guarded = [];

    //时间转换
    const CREATED_AT = 'create_time';

    /**
     * 提心记录
     * @param $userInfo
     * @param string $field
     * @param $status
     * @param $limit
     * @return mixed
     */
    public static function getCushLists($userInfo, $field="*", $status, $page, $limit){
        $map = ['withdraw.user_id'=>$userInfo['id'],'withdraw.is_delete'=>0];
        $status > 0 && $map['withdraw.status'] = $status;
        return self::select($field)
            ->where($map)
            ->leftJoin('user','withdraw.user_id','=','user.id')
            ->orderBy('withdraw.id','desc')
            ->paginate($limit);
    }

    /**
     * 提现列表
     * @param $goodsId
     * @param $keywords
     * @param $page
     * @param $limit
     * @return mixed
     */
    public function getWithdrawList($userId,$keywords,$page,$limit){
        $map = ['is_delete'=>0];
        $userId > 0 && $map['user_id'] = $userId;
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

    /**
     * 获取提现信息
     * @param $id
     * @return mixed
     */
    public static function getWalletdrawInfo($id){
        $map = ['id'=>$id,'is_delete'=>0];
        return self::where($map)->first();
    }

    /**
     * 更新提现状态
     * @param $data
     * @return mixed
     */
    public function updateStatus($data){
        return self::where('user_id',$data['id'])->update($data);
    }
}
