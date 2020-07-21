<?php


namespace App\Http\Controllers\Api\V1;

use App\Http\Service\ExchangeCateService;
use App\Http\Service\ExchangeService;
use App\Library\Render;
use App\Models\Exchange;
use Illuminate\Http\Request;

/**
 * 商品兑换
 * Class ExchangeController
 * @package App\Http\Controllers\Api\V1
 */
class ExchangeController extends BaseController
{

    private $exchangeService;   //兑换服务层
    private $exchangeCateService;   //兑换分类服务层
    /**
     * ExchangeController constructor.
     */
    public function __construct()
    {
        $this->exchangeService = isset($this->exchangeService) ?: new ExchangeService();
        $this->exchangeCateService = isset($this->exchangeCateService) ?: new ExchangeCateService();
    }

    /**
     * 兑换商品分类
     * @return \Illuminate\Http\JsonResponse
     */
   public function getCatelist(){
       $lists = $this->exchangeCateService->getCateList();
       return Render::success('获取成功',$lists);
   }

    /**
     * 兑换商品列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
   public function getGoodsLists(Request $request){
       $page = $request->input('page',1);
       $limit = $request->input('limit',10);
       $cateType = $request->input('cateType',0);
       $lists = $this->exchangeService->getApiCateLists($cateType,$page,$limit);
       return Render::success('获取成功',$lists);
   }

    /**
     * 获取兑换商品详情
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
   public function getApiGoodsDetail(Request $request){
       $id = $request->input('id',0);
       $detail = Exchange::getApiGoodsDetail($id);
       return Render::success('获取成功',$detail);
   }

    /**
     * 商品兑换
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
   public function createOrder(Request $request){
       $goods_id = $request->input('goods_id',0);
       if ($this->exchangeService->createOrder($this->userInfo,$goods_id)){
           return Render::success('兑换成功');
       }
       return  Render::error($this->exchangeService->getErrorMsg() ?: '兑换失败');
   }
}
