<?php


namespace App\Http\Controllers\Api\V1;


use App\Http\Service\NewsService;
use App\Library\Render;
use Illuminate\Http\Request;

/**
 * 新闻控制器
 * Class NewsController
 * @package App\Http\Controllers\Api
 */
class NewsController extends BaseController
{
    //新闻
    private $newsService;

    /**
     * IndexController constructor.
     */
    public function __construct()
    {
        $this->newsService = isset($this->newsService) ?: new NewsService();
    }

    /**
     * 新闻列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
   public function getNewsPageLists(Request $request){
        $limit = $request->input('limit',10);
        $page = $request->input('page',1);
        $data = $this->newsService->getNewsPageLists($limit);
        return Render::success('获取成功',$data);
   }

    /**
     * 新闻详情
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
   public function getNewsDetail($id){
       $detail = $this->newsService->getNewsDetail($id);
       return Render::success('获取成功',$detail);
   }
}
