<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use phpDocumentor\Reflection\Types\Self_;

/**
 * 兑换商品模型
 * Class Goods
 * @package App\Models
 */
class Exchange extends Model
{
    //定义模型关联表
    protected $table = 'hp_exchange_goods';
    //时间转换
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';

    //隐藏字段
    protected $hidden = ['is_delete'];
    //设置保存字段
    protected $guarded  = ['mulPic'];

    public function getGoodsStatusAttribute($value){
        $data = [
            10=>['status'=>10,'status_name'=>"正常"],
            20=>['status'=>20,'status_name'=>"下架"]
        ];
        return $data[$value];
    }
    //新品获取器
    public function getIsNewAttribute($value){
        $data = [
            0=>['status'=>0,'status_name'=>"正常"],
            1=>['status'=>1,'status_name'=>"新品"]
        ];
        return $data[$value];
    }
    //热门获取器
    public function getIsHotAttribute($value){
        $data = [
            0=>['status'=>0,'status_name'=>"正常"],
            1=>['status'=>1,'status_name'=>"热门"]
        ];
        return $data[$value];
    }
    //推荐获取器
    public function getIsRecommendAttribute($value){
        $data = [
            0=>['status'=>0,'status_name'=>"正常"],
            1=>['status'=>1,'status_name'=>"推荐"]
        ];
        return $data[$value];
    }

    /**
     * 关联商品轮播图
     * @return \Illuminate\Database\Eloquent\Relations\HasMany/
     */
    public function picture(){
        return $this->hasMany('App\Models\Picture','pic_id','id')
            ->select(['id','pic_id','pic_url'])
            ->where(['is_delete'=>0])
            ->where(['pic_type'=>Config::get('constants.PIC_GOODS_TYPE')]);
    }
    /**
     * 商品列表
     * @param $keyword
     * @param $limit
     * @return mixed
     */
    public function getGoodsLists($keyword,$limit){
        //查询
        $field = ['id','goods_no','goods_name','goods_cover','sales_score','line_score','sales_num','stock_num',
            'cate_id','sort','status','goods_desc','update_user_name', 'create_user_name','update_time','create_time'];
        $lists = self::where(['is_delete'=>0])
            ->when(!empty($keyword),function ($query) use ($keyword){
                return $query->where('goods_name','like','%'.$keyword.'%')
                    ->orWhere('goods_no','like','%'.$keyword.'%');
            })
            ->whereIn('status',[10,20])
            ->select($field)
            ->orderBy('id','desc')
            ->paginate($limit);
        return $lists;
    }

    /**
     * 商品详情
     * @param $id
     * @return mixed
     */
    public function getGoodsDetailById($id){
        $map = ['is_delete'=>0,'id'=>$id];
        return self::where($map)->with('picture')->first();
    }

    /**
     * 添加商品
     * @param $data
     * @return mixed
     */
    public function addGoods($data){
        return self::create($data);
    }

    /**
     * 首页新品推荐
     * @return mixed
     */
    public function getNewsGoods(){
        $map = ['is_new'=>0,'goods_status'=>10,'is_delete'=>0];
        $field = ['id','goods_name','goods_cover','good_price'];
        return self::select($field)->where($map)->orderBy('sort')->take(4)->get();
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

    /**
     * 更新商品
     * @param $data
     * @return mixed
     */
    public function updateGoods($data){
        return self::where(['id'=>$data['id']])->update($data);
    }

    /**
     * 兑换商品分类
     * @return mixed
     */
    public function getApiCateLists(){
        $field = ['id','goods_no','goods_name','goods_cover','cate_id','sales_score','line_score',
            'sales_num'];
        $map = ['is_delete'=>0,'status'=>10];
        return self::select($field)
            ->where($map)
            ->orderBy('sort')
            ->orderBy('create_time','desc')
            ->get();
    }

    /**
     * 兑换商品详情
     * @param array|null $id
     * @return mixed
     */
    public static function getApiGoodsDetail(?array $id)
    {
        $field = ['id','goods_no','goods_name','goods_cover','cate_id','sales_score','line_score',
            'sales_num','content','stock_num'];
        $map = ['is_delete'=>0,'status'=>10];
        return self::select($field)->where($map)->first();
    }

    /**
     * 更新商品兑换数量
     * @param $goodsId
     * @return mixed
     */
    public function updateExchangeNum($goodsId){
        return self::where(['id' => $goodsId])->increment('sales_num');
    }

    /**
     * 减少库存
     * @param $goodsId
     * @return mixed
     */
    public function updateExchangeStock($goodsId){
        return self::where(['id' => $goodsId])->decrement('stock_num');
    }

}
