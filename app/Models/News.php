<?php

namespace App\Models;


/**
 * 新闻模型
 * Class News
 * @package App\Models
 */
class News extends Base
{    //定义模型关联表
    protected $table = 'hp_news';
    //时间转换
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';

    protected $guarded = [];
    /**
     * 后端新闻列表
     * @param $keywords
     * @param $limit
     * @return mixed
     */
    public function getAdminNewsLists($keywords,$limit){
        $map = ['is_delete'=>0];
        $field = ['id','news_title','news_desc','news_cover','content','create_time','read_num',
            'status','is_recommend','update_time','create_time','update_user_name'];
        return self::select($field)
            ->when(!empty($keywords),function ($query) use ($keywords){
                return $query->where('news_title','like','%'.$keywords.'%');
            })
            ->where($map)
            ->orderBy('sort','desc')
            ->paginate($limit);
    }

    /**
     * 添加新闻
     * @param array $data
     * @return mixed
     */
    public function addNews(array $data)
    {
        return self::create($data);
    }
    /**
     * 修改新闻
     * @param array $data
     * @return mixed
     */
    public function editNews(array $data)
    {
        return self::where(["id"=>$data['id']])->update($data);
    }

    /**
     * 后端新闻详情
     * @param $id
     * @return mixed
     */
    public static function getAdminNewsById($id){
        $map = ['status'=>10,'is_delete'=>0,'id'=>$id];
        $field = ['id','news_title','news_cover','news_desc','sort','read_num','content','create_time'];
        return self::select($field)->where($map)->first();
    }

    /**
     * 批量删除新闻
     * @param $data
     * @param array $ids
     * @return mixed
     */
    public function delBatch($data, array $ids)
    {
        return self::whereIn('id',$ids)->update($data);
    }
    /**
     * 修改新闻状态
     * @param array $data
     * @return mixed
     */
    public function updateStatus(array $data)
    {
        return self::where(['id'=>$data['id']])->update($data);
    }

    /**
     * 修改推荐状态
     * @param array $data
     * @param $loginInfo
     * @return mixed
     */
    public function updateIsRecommend(array $data, $loginInfo)
    {
        return self::where(['id'=>$data['id']])->update($data);
    }

    /*--------------------------------小程序----------------------------------*/

    /**
     * 小程序首页新品推荐
     * @return mixed
     */
    public function getNewsLists(){
        $map = ['status'=>10,'is_delete'=>0,'is_recommend'=>1];
        $field = ['id','news_title','update_time'];
        return self::select($field)->where($map)->orderBy('sort','desc')->take(4)->get();
    }

    /**
     * 小程序分页列表
     * @param $limit
     * @return mixed
     */
    public function getNewsPageLists($limit)
    {
        $map = ['status'=>10,'is_delete'=>0];
        $field = ['id','news_title','news_desc','news_cover','create_time'];
        return self::select($field)->where($map)->orderBy('sort','desc')->paginate($limit);
    }

    /**
     *新闻详情
     * @param $id
     * @return mixed
     */
    public static function getNewsDetail($id)
    {
        $map = ['status'=>10,'is_delete'=>0,'id'=>$id];
        $field = ['id','news_title','news_cover','news_desc','read_num','content','create_time'];
        self::where('id',$id)->increment('read_num');
        return self::select($field)->where($map)->first();
    }


}
