<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

/**
 * 商品模型
 * Class Goods
 * @package App\Models
 */
class Goods extends Model
{
    //定义模型关联表
    protected $table = 'hp_goods';
    //时间转换
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';
    //时间格式
    protected $dateFormat = 'U';
    //隐藏字段
    protected $hidden = ['is_delete'];
    //设置保存字段
    protected $guarded  = ['mulPic'];

    //状态获取器
    public function getGoodsStatusAttribute($value){
        if ($value == 10){
            $this->attributes['goods_status'] = [
                'status' => $value,
                'status_name' => '正常'
            ];
        }else{
            $this->attributes['goods_status'] = [
                'status' => $value,
                'status_name' => '下架'
            ];
        }
        return $this->attributes['goods_status'];
    }
    //新品获取器
    public function getIsNewAttribute($value){
        if ($value == 0){
            $this->attributes['is_new'] = [
                'status' => $value,
                'status_name' => '正常'
            ];
        }else{
            $this->attributes['is_new'] = [
                'status' => $value,
                'status_name' => '新品'
            ];
        }
        return $this->attributes['is_new'];
    }
    //热门获取器
    public function getIsHotAttribute($value){
        if ($value == 0){
            $this->attributes['is_hot'] = [
                'status' => $value,
                'status_name' => '正常'
            ];
        }else{
            $this->attributes['is_hot'] = [
                'status' => $value,
                'status_name' => '热门'
            ];
        }
        return $this->attributes['is_hot'];
    }
    //推荐获取器
    public function getIsRecommendAttribute($value){
        if ($value == 0){
            $this->attributes['is_recommend'] = [
                'status' => $value,
                'status_name' => '正常'
            ];
        }else{
            $this->attributes['is_recommend'] = [
                'status' => $value,
                'status_name' => '推荐'
            ];
        }
        return $this->attributes['is_recommend'];
    }

    /**
     * 关联商品轮播图
     * @return \Illuminate\Database\Eloquent\Relations\HasMany/
     */
    public function picture(){
        return $this->hasMany('App\Models\Picture','pic_id','id')
            ->select(['id','pic_id','pic_url'])
            ->where(['pic_type'=>Config::get('PIC_GOODS_TYPE')])
            ->limit(6)
            ->withDefault();
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
            ->with('picture')
            ->paginate($limit);
        return $lists;
    }

    /**
     * 商品详情
     * @param $id
     * @return mixed
     */
    public function getGoodsDetailById($id){
        $map = ['is_delete'=>0];
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


}
