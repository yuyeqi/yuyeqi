<?php


namespace App\Http\Service;

use App\Models\Goods;
use App\Models\Picture;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

/**
 * 商品service
 * Class GoodsService
 * @package App\Http\Service
 */
class GoodsService extends BaseSerivce
{
    //商品模型
    protected $goods = null;

    //图片模型
    protected $picture = null;
    /**
     * GoodsService constructor.
     */
    public function __construct()
    {
        $this->goods = isset($this->goods) ?: new Goods();
        $this->picture = isset($this->picture) ?: new Picture();
    }

    /**
     * 商品列表
     * @param $keywords
     * @param $limit
     * @return mixed
     */
    public function getGoodsLists($keywords,$limit){
        return $this->goods->getGoodsLists($keywords,$limit);
    }

    /**
     * 商品详情
     * @param $id
     * @return mixed
     */
    public function getGoodsDetailById($id){
        return $this->goods->getGoodsDetailById($id);
    }

    public function addGoods($data,$loginInfo){
        //商品数据
        $data['create_id'] = $loginInfo['id'];
        $data['create_user_name'] = $loginInfo['username'];
        $data['update_id'] = $loginInfo['id'];
        $data['update_user_name'] = $loginInfo['username'];
        //保存商品数据
        //开启事务
        DB::beginTransaction();
        try {
            $res = $this->goods->addGoods($data);//添加轮播图
            $img = [];
            if (isset($data['mulPic']) && is_array($data['mulPic']) && !empty($data['mulPic'])) {
                foreach ($data['mulPic'] as $key => $item) {
                    $img[$key]['pic_id'] = $res->id;
                    $img[$key]['pic_type'] = Config::get('constants.PIC_GOODS_TYPE');
                    $img[$key]['pic_url'] = $item;
                }
                $this->picture->addPicture($img);
            }
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->setErrorCode(0);
            $this->setErrorMsg($e.$this->getErrorMsg());
            return false;
        }
    }
}
