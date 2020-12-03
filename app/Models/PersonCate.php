<?php

namespace App\Models;


/**
 * 私人定制分类模型
 * Class Cases
 * @package App\Models
 */
class PersonCate extends Base
{    //定义模型关联表
    protected $table = 'person_cate';
    //时间转换
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';


    /*--------------------------------小程序----------------------------------*/

    /**
     * 私人定制分类
     * @return mixed
     */
    public function getPersonCateLists(){
        $map = ['status'=>10,'is_delete'=>0];
        $field = ['id','cate_name','bg_url'];
        return self::select($field)->where($map)->orderBy('sort')->orderBy('id')->get();
    }

    /**
     * 分类列表
     * @param string $keywords
     * @param int $limit
     * @return mixed
     */
    public function getPersonCateAdminLists(string $keywords, int $limit)
    {
        $map = ['is_delete'=>0];
        $field = ['id','cate_name','bg_url', 'status','sort','update_time','create_time','update_user_name'];
        return self::select($field)
            ->when(!empty($keywords),function ($query) use ($keywords){
                return $query->where('cate_name','like','%'.$keywords.'%');
            })
            ->where($map)
            ->orderBy('sort','desc')
            ->paginate($limit);
    }

    /*--------------------------------------后端------------------------------*/
    /**
     * 添加分类
     * @param array $data
     * @return mixed
     */
    public function addPersonCate(array $data)
    {
        return self::create($data);
    }

    /**
     * 分类详情
     * @param $id
     * @return mixed
     */
    public function getPersonCateById($id)
    {
        $map = ['status'=>10,'is_delete'=>0,'id'=>$id];
        $field = ['id','cate_name','bg_url','sort'];
        return self::select($field)->where($map)->first();
    }

    /**
     * 私人定制分类
     * @return mixed
     */
    public function getPersonCateSelectLists(){
        $map = ['is_delete'=>0];
        $field = ['id','cate_name','bg_url'];
        return self::select($field)->where($map)->orderBy('sort')->orderBy('id')->get();
    }

    /**
     * 更新分类
     * @param array $data
     * @return mixed
     */
    public function editPersonCate(array $data)
    {
        return self::where(['id'=>$data['id']])->update($data);
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
