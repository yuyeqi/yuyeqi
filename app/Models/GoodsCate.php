<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

/**
 * 商品分类模型
 * Class Goods
 * @package App\Models
 */
class GoodsCate extends Base
{
    //定义模型关联表
    protected $table = 'hp_goods_cate';
    //时间转换
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';
    //时间格式
    protected $dateFormat = 'U';
    //隐藏字段
    protected $hidden = ['is_delete'];

    //设置保存字段
    protected $guarded  = [];
    /**
     * 商品分类列表
     * @param $keywords
     * @param $limit
     * @return mixed
     */
    public function getLists($keywords,$limit){
        return self::where(['is_delete'=>0])
            ->orderBy('sort')
            ->orderBy('id')
            ->paginate($limit);
    }

    /**
     * 添加
     * @param $data
     * @return mixed
     */
    public function add($data){
        return self::create($data);
    }

    /**
     * 分类详情
     * @param $id
     * @return mixed
     */
    public function getDetailById($id)
    {
        $map = ['is_delete'=>0,'id'=>$id];
        $field = ['id','cate_name','sort','create_time','create_time'];
        return self::select($field)->where($map)->first();
    }

    /**
     * 修改
     * @param array $data
     * @return mixed
     */
    public function edit(array $data)
    {
        return self::where(["id"=>$data['id']])->update($data);
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
     * 商品分类列表
     * @return mixed
     */
    public function getCateLists()
    {
        $map = ['status'=>10,'is_delete'=>0];
        return self::where($map)->get();
    }

}
