<?php


namespace App\Models;


use App\Http\Controllers\Admin\BaseController;
use Illuminate\Support\Facades\Config;

/**
 * 商品模型
 * Class Goods
 * @package App\Models
 */
class Goods extends BaseController
{
    //定义模型关联表
    protected $table = 'hp_goods';
    //时间转换
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';

    //隐藏字段
    protected $hidden = ['is_delete'];
    //设置保存字段
    protected $guarded  = ['mulPic'];

    //状态获取器
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

    /**关联评论
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comment(){
        return $this->hasMany('App\Models\GoodsComment','goods_id','id')
                ->select(['id','user_id','goods_id','comment_content','create_time'])
                ->orderBy("is_top",'desc')
                ->orderBy("sort",'desc')
                ->orderBy("id",'desc')
                ->where(['is_delete'=>0,'status'=>10])
                ->take(2);
    }
    /**
     * 商品列表
     * @param $keyword
     * @param $limit
     * @return mixed
     */
    public function getGoodsLists($keyword,$limit){
        //查询
        $field = ['id','goods_no','goods_name','goods_cover','good_price','score','book_price','sales_actual',
            'cate_id','is_new','is_hot','is_recommend','sort','goods_status','comment_num','update_user_name',
            'create_user_name','update_time','create_time'];
        $lists = self::where(['is_delete'=>0])
            ->when(!empty($keyword),function ($query) use ($keyword){
                return $query->where('goods_name','like','%'.$keyword.'%')
                    ->orWhere('goods_no','like','%'.$keyword.'%');
            })
            ->whereIn('goods_status',[10,20])
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
        return self::select($field)->where($map)->orderBy('sort','desc')->take(4)->get();
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

    /*--------------------------------------小程序----------------------------------------*/
    /**
     * 小程序商城列表
     * @param $keywords
     * @param $cateId
     * @param $page
     * @param $limit
     * @return mixed
     */
    public function getShopLists($keywords,$cateId,$sort,$page,$limit){
        //输出字段
        $field = ['id','goods_no','goods_name','goods_cover','good_price','score','book_price','sales_actual',
            'cate_id','sort','comment_num'];
        //设置查询条件
        $map = ['is_delete'=>0,'goods_status'=>10];
        $cateId>0 && $map['cate_id'] = $cateId;
        //设置排序
        $sortField = "sort";
        if ($sort == "hot"){
            $sortField = "is_hot";
        }else if ($sort == "sales"){
            $sortField = "sales_actual";
        }else if ($sort == "price"){
            $sortField = "good_price";
        }else if ($sort == "news"){
            $sortField = "is_new";
        }
        $lists = self::where($map)
            ->when(!empty($keywords),function ($query) use ($keywords){
                return $query->where('goods_name','like','%'.$keywords.'%');
            })
            ->select($field)
            ->orderBy($sortField,'desc')
            ->paginate($limit);
        return $lists;
    }


    /**
     * 商品详情页
     * @param $id
     * @return mixed
     */
    public function getApiGoodsDetail($id){
        //输出字段
        $field = ['id','goods_no','goods_cover','goods_name','goods_cover','good_price','score','book_price','sales_actual','goods_desc','comment_num','goods_content'];
        //where条件
        $map = ['id'=>$id,'is_delete'=>0,'goods_status'=>10];
        return self::select($field)
            ->where($map)
            ->with(['picture','comment.user'])
            ->first();
    }

    /**
     * 商品详情不带评论
     * @param $id
     * @return mixed
     */
    public function getApiGoodsDetailById($id){
        //输出字段
        $field = ['id','goods_no','goods_cover','goods_name','goods_cover','good_price','score','book_price','sales_actual','is_delete','goods_status'];
        //where条件
        $map = ['id'=>$id,'is_delete'=>0,'goods_status'=>10];
        return self::select($field)
            ->where($map)
            ->first();
    }

}
