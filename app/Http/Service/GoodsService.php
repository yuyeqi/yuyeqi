<?php


namespace App\Http\Service;

use App\Models\Goods;

/**
 * 商品service
 * Class GoodsService
 * @package App\Http\Service
 */
class GoodsService extends BaseSerivce
{
    //商品模型
    protected $goods = null;

    /**
     * GoodsService constructor.
     */
    public function __construct()
    {
        $this->goods = isset($this->goods) ?: new Goods();
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

}
