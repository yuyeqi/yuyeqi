<?php

namespace App\Models;

use Carbon\Carbon;

/**
 * 报备模型
 * Class Slideshow
 * @package App\Models
 */
class Book extends Base
{
    //定义模型关联表
    protected $table = 'hp_book';
    //时间转换
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';

    /**
     * 首页轮播图列表
     * @return int
     */
     public function getSlideshowLists(){
         return self::where(['status'=>0,'is_delete'=>0])
             ->orderBy('sort')
             ->orderBy('id')
             ->take(5)->get();
     }

    /**
     * 后端轮播图列表
     * @param int $limit
     * @return mixed
     */
    public function getSlideshowAdminLists($keywords, $limit)
    {
        return self::where(['is_delete'=>0])
            ->when(!empty($keywords),function ($query) use ($keywords){
                return $query->where('slideshow_name','like','%'.$keywords.'%');
            })
            ->orderBy('sort')
            ->orderBy('id')
            ->paginate($limit);
    }

    /**
     * 添加
     * @param array $data
     * @return mixed
     */
    public function addSlideshow(array $data)
    {
        return self::create($data);
    }

    /**
     * 轮播详情
     * @param $id
     * @return mixed
     */
    public function getAdminSlideshowById($id)
    {
        $map = ['id'=>$id,'is_delete'=>0];
        return self::where($map)->first();
    }

    /**
     * 修改轮播
     * @param array $data
     * @return mixed
     */
    public function editSlideshow(array $data)
    {
        return self::where('id',$data['id'])->update($data);
    }

    /**
     * 批量删除
     * @param $data
     * @param array $ids
     * @return mixed
     */
    public function delBatch($data, array $ids)
    {
        return self::whereIn('id',$ids)->update($data);
    }

    /**
     * 修改状态
     * @param array $data
     * @return mixed
     */
    public function updateStatus(array $data)
    {
        return self::where(['id'=>$data['id']])->update($data);
    }

    /**
     * 预约列表
     * @param array $data
     * @param int $limit
     */
    public function getBookAdminLists(array $data, int $limit)
    {
        //设置搜索条件
        $map = ['is_delete'=>0];
        $startTime = '';
        $endTime = '';
        $dealStartTime = '';
        $dealEndTime = '';
        !isset($data['book_no']) ?: $map['book_no'] = $data['book_no'];
        !isset($data['client_name']) ?: $map['client_name'] = ['like','%'.$data['book_no'].'%'];
        !isset($data['client_phone']) ?: $map['client_phone'] = ['like','%'.$data['client_phone'].'%'];
        !isset($data['community']) ?: $map['community'] = ['like','%'.$data['community'].'%'];
        !isset($data['house_name']) ?: $map['house_name'] = ['like','%'.$data['house_name'].'%'];
        !isset($data['user_name']) ?: $map['user_name'] = ['like','%'.$data['user_name'].'%'];
        !isset($data['status']) ?: $map['status'] = $data['status'];
        !isset($data['start_time']) ?: $startTime = $data['start_time'];
        !isset($data['end_time']) ?: $endTime = $data['end_time'];
        !isset($data['deal_start_time']) ?: $dealStartTime = $data['deal_start_time'];
        !isset($data['deal_end_time']) ?: $dealEndTime = $data['deal_end_time'];
        return self::where($map)
            ->when(!empty($startTime),function ($query) use ($startTime){
                return $query->whereDate('create_time','<=',$startTime);
            })
            ->when(!empty($endTime),function ($query) use ($endTime){
                return $query->whereDate('create_time','>=',$endTime);
            })
            ->when(!empty($dealStartTime),function ($query) use ($dealStartTime){
                return $query->whereDate('deal_finished_time','>=',$dealStartTime);
            })
            ->when(!empty($dealEndTime),function ($query) use ($dealEndTime){
                return $query->whereDate('deal_finished_time','<=',$dealEndTime);
            })
            ->orderBy('create_time','desc')
            ->paginate($limit);
    }


    /*--------------------------------------------------小程序----------------------------*/
    /**
     * 预约列表
     * @param $userInfo
     * @param $page
     * @param $limit
     * @return mixed
     */
    public function getApiBookLists($userInfo,$page,$limit){
        $map = ['user_id'=>$userInfo['id'],'is_delete'=>0];
        $field = ['id','book_no','client_name','client_phone','status','create_time'];
        return self::select($field)
            ->where($map)
            ->orderBy('create_time','desc')
            ->paginate($limit);
    }

    /**
     * 客户预约
     * @param $data
     * @return mixed
     */
    public function addBook($data){
        return self::create($data);
    }

    /**
     * 获取当天的最大的预约号
     * @return mixed
     */
    public static function getBookNum()
    {
        return self::whereDay ('create_time',date('d'))
            ->where(['is_delete'=>0])
            ->orderBy('create_time','desc')
            ->value('book_no');
    }

    /**
     *客户预约详情
     * @param $id
     * @return mixed
     */
    public static function getApiBookDetail($id){
        $map = ['id'=>$id,'is_delete'=>0];
        $field = ['*'];
        return self::select($field)
            ->where($map)
            ->first();
    }
}
