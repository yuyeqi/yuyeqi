<?php


namespace App\Http\Controllers\Api\V1;


use App\Http\Service\CasesService;
use App\Http\Service\GoodsService;
use App\Http\Service\NewsService;
use App\Http\Service\SlideshowService;
use App\Http\Service\UserService;
use App\Library\Render;
use App\Models\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * 首页控制器
 * Class IndexController
 * @package App\Http\Controllers\Api
 */
class IndexController extends BaseController
{
    //轮播
    private $slideshowService;
    //用户
    private $userService;
    //商品
    private $goodsSerivice;
    //新闻
    private $newsService;
    //案例
    private $casesService;

    /**
     * IndexController constructor.
     */
    public function __construct()
    {
        parent:: __construct();
        $this->slideshowService = isset($this->slideshowService) ?: new SlideshowService();
        $this->userService = isset($this->userService) ?: new UserService();
        $this->goodsSerivice = isset($this->goodsSerivice) ?: new GoodsService();
        $this->newsService = isset($this->newsService) ?: new NewsService();
        $this->casesService = isset($this->casesService) ?: new CasesService();
    }

    /**
     * 小程序获取轮播图
     */
    public function getSlideShowLists(){
        $data = $this->slideshowService->getSlideshowList();
        return Render::success('获取成功',$data);
    }

    /**
     * 小程序获取用户信息
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserInfo(){
        $userinfo = $this->userService->getUserInfo($this->userInfo['id']);
        $data['userinfo'] = $userinfo;
        $welcomeMsg = Config::getConfigByNo('welcomeMsg')->content;
        return Render::success('获取成功',compact('userinfo','welcomeMsg'));
    }

    /**
     * 小程序首页新品推荐
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNewsGoods(){
        $newsGoods = $this->goodsSerivice->getNesGoods();
        return Render::success('获取成功',$newsGoods);
    }

    /**
     * 小程序首页新闻列表
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNewsLists(){
        $newsLists = $this->newsService->getNewsLists();
        return Render::success('获取成功',$newsLists);
    }

    /**
     * 小程序首页案例列表
     * @param Request $request
     */
    public function getCaseLists(Request $request){
        $limit =  $request->input('limit',10);
        $page =  $request->input('page',1);
        $caseLists = $this->casesService->getCasesLists($limit);
        return Render::success('获取成功',$caseLists);
    }

    /**
     * 案例详情
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCasesDetail(Request $request){
        $id = $request->input('id',0);
        if ($id <= 0){
            Log::error('【案例详情】------参数错误无，缺少id');
            return  Render::error("参数错误，请重试!");
        }
        $detail = $this->casesService->getCasesDetail($id);
        return Render::success('获取成功',$detail);
    }
}
