<?php


namespace App\Http\Controllers\Api\V1;



use App\Http\Service\GoodsCateService;
use App\Http\Service\GoodsService;
use App\Library\Render;
use Illuminate\Http\Request;

/**
 * 商城API控制器
 * Class PersonController
 * @package App\Http\Controllers\Api
 */
class ShopController extends BaseController
{
    private $goodsService;   //商品服务层
    private $goodsCateService; //商品分类服务层

    /**
     * ShopController constructor.
     */
    public function __construct()
    {
        $this->goodsService = isset($this->goodsService) ?: new GoodsService();
        $this->goodsCateService = isset($this->goodsCateService) ?: new GoodsCateService();
    }

    /**
     * API商城列表页
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLists(Request $request){
        //接收参数
        $keywords = $request->input("keywords",""); //关键字
        $cateId = $request->input("cateId",0);
        $sort = $request->input("sort","");
        $page = $request->input("page",1);
        $limit = $request->input("limit",10);
        $lists = $this->goodsService->getLists($keywords,$cateId,$sort,$page,$limit);
        return Render::success("获取成功",$lists);
    }

    /**
     * 商城分类列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCateLists(Request $request){
        $lists = $this->goodsCateService->getApiCateLists();
        return Render::success("获取成功",$lists);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse商品详情
     */
    public function getShopDetail($id){
        $detail = $this->goodsService->getApiGoodsDetail($id);
        return Render::success("获取成功",$detail);
    }
}
