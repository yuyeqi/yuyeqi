<?php


namespace App\Models;


use Illuminate\Support\Facades\Config;
use phpDocumentor\Reflection\Types\Self_;

/**
 * 商品评论模型
 * Class Goods
 * @package App\Models
 */
class GoodsComment extends Base
{
    //定义模型关联表
    protected $table = 'hp_goods_comment';
    //时间转换
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';

    //隐藏字段
    protected $hidden = ['is_delete'];

    /**
     * 关联用户
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function user(){
        return $this->hasOne('App\Models\User','id','user_id')
            ->select(['id','nick_name','avatar_url'])
            ->where(['is_delete'=>0]);
    }

    /**
     * 关联商品轮播图
     * @return \Illuminate\Database\Eloquent\Relations\HasMany/
     */
    public function picture(){
        return $this->hasMany('App\Models\Picture','pic_id','id')
            ->select(['id','pic_id','pic_url'])
            ->where(['is_delete'=>0])
            ->where(['pic_type'=>4]);
    }
    /**
     * 添加评论
     * @param $data
     * @return mixed
     */
    public function addComment($data){
        return self::create($data);
    }

    /**
     * 评论列表
     * @param $goods_id
     * @param $page
     * @param $limit
     * @return mixed
     */
    public function getCommentList($goods_id,$page,$limit){
        $map = ['is_delete'=>0,'status'=>10,'goods_id'=>$goods_id];
        $field = ['id','user_name','avatar_url','comment_content','create_time'];
        return self::select($field)
            ->where($map)
            ->with(['picture'])
            ->orderBy('is_top','desc')
            ->orderBy('sort','desc')
            ->orderBy('create_time','desc')
            ->paginate($limit);
    }

    /**
     * 后端评论列表
     * @param $keywords
     * @param $page
     * @param $limit
     * @return mixed
     */
    public function getCommentLists($keywords,$page,$limit){
        $map = ['is_delete'=>0];
        $field = ['*'];
        return self::select($field)
            ->where($map)
            ->when(!empty($keywords),function ($query) use ($keywords){
                return $query->where('user_name','like','%'.$keywords.'%')
                    ->orWhere('goods_name','like','%'.$keywords.'%');
            })
            ->with(['picture'])
            ->orderBy('is_top','desc')
            ->orderBy('sort','desc')
            ->orderBy('create_time','desc')
            ->paginate($limit);
    }

    /**
     * 更新状态
     * @param $data
     * @return mixed
     */
    public function updateStatus($data){
        return self::where(["id"=>$data['id']])->update($data);
    }

    /**
     * 批量删除数据
     * @param $ids
     * @param $data
     * @return mixed
     */
    public function delBatch($ids,$data){
        return self::whereIn("id",$ids)->update($data);
    }
}
