<?php

namespace App\Models;


use phpDocumentor\Reflection\Types\Self_;

/**
 * 用户分类模型
 * Class User
 * @package App\Models
 */
class UserCate extends Base
{    //定义模型关联表
    protected $table = 'hp_user_cate';
    //时间转换
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';

    protected $guarded = [];

    /**
     * 用户分类
     * @param $limit
     * @return mixed
     */
    public function getLists($keyword,$limit){
        $field = ['id','cate_name','status','register_account','tg_account','book_score','create_time','update_time',
            'store_score','order_score','store_score','order_score','update_user_name','create_user_name'];
        return self::select($field)
            ->where(['is_delete'=>0])
            ->when(!empty($keyword),function ($query) use ($keyword){
                return $query->where('cate_name','like','%'.$keyword.'%');
            })
            ->orderBy('id','desc')
            ->paginate($limit);
    }

    /**
     * 用户分类详情
     * @param $id
     * @return mixed
     */
    public function getDetail($id){
        $map = ['id'=>$id,'is_delete'=>0];
        $field = ['id','cate_name','status','register_account','tg_account','book_score','create_time','update_time',
            'store_score','order_score','store_score','order_score','update_user_name','create_user_name'];
        return self::select($field)
            ->where($map)
            ->first();
    }

    /**
     * 添加分类
     * @param $data
     * @return mixed
     */
    public function add($data){
        return self::create($data);
    }

    /**
     * 修改
     * @param $data
     * @param $id
     * @return mixed
     */
    public function edit($data){
        return self::where(['id'=>$data['id']])->update($data);
    }

    /**
     * 批量删除
     * @param $ids
     * @param $data
     * @return mixed
     */
    public function delBitch($ids,$data){
        return self::whereIn('id',$ids)->update($data);
    }

    /**
     * 更新分类状态
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
    public function getUserInfo($id){
        $map = ['status'=>0,'is_delete'=>0,'id'=>$id];
        $field = ['id','nick_name','avatar_url','user_type'];
        return self::select($field)->where($map)->with('userStatistic')->first();
    }

    /**
     * 根据用户分类获取用户分类的信息
     * @param $userType
     * @return mixed
     */
    public static function getUserCateInfoByUserType($userType){
        $map = ['status'=>10,'is_delete'=>0,'id'=>$userType];
        $field = ['id','cate_name','status','register_account','tg_account','book_score',
            'store_score','order_score'];
        return self::where($map)->first($field);
    }

    /**
     * 用户列表
     * @return mixed
     */
    public function getUserCateLists()
    {
        $map = ['is_delete'=>0, 'status'=>0];
        $field = ['id','cate_name','cate_type'];
        return self::select($field)->where($map)->orderBy('id')->get();
    }

}
