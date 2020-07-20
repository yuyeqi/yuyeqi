<?php

namespace App\Models;


/**
 * 轮播图模型
 * Class Slideshow
 * @package App\Models
 */
class Slideshow extends Base
{
    //定义模型关联表
    protected $table = 'hp_slideshow';
    //时间转换
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';

    protected  $hidden = [
        'is_delete','update_user_id','create_user_id','create_user_name'
    ];

    /**
     * 首页轮播图列表
     * @return int
     */
     public function getSlideshowLists(){
         $field = ['id','slideshow_url'];
         return self::select($field)
             ->where(['status'=>10,'is_delete'=>0])
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
}
