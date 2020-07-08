<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\NewsValidator;
use App\Http\Service\NewsService;
use App\Library\Render;
use Illuminate\Http\Request;

/**
 * 后台新闻控制器
 * Class NewsController
 * @package App\Http\Controllers\Admin
 */
class NewsController extends BaseController
{
    //新闻服务层
    private $newsService;

    /**
     * GoodsController constructor.
     */
    public function __construct()
    {
        parent:: __construct();
        $this->newsService = isset($this->newsService) ?: new NewsService();
    }

    /**
     * 新闻列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){
        return view('admin.news.index');
    }
    /**
     * 新闻列表
     * @param Request $request
     */
    public function getNewsLists(Request $request){
        //接收参数
        $keyword = trim($request->get('keywords',''));
        $limit = intval($request->get('limit','10'));
        $lists = $this->newsService->getAdminNewsLists($keyword,$limit);
        return Render::table($lists->items(),$lists->total());
    }

    /**
     * 添加新闻
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addShow(){
        return view('admin.news.add');
    }

    /**
     * 添加数据
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(NewsValidator $request){
        $data = $request->only(['news_title','news_cover','news_desc','sort','content']);
        //添加数据
        try {
            $result = $this->newsService->addNews($data, $this->loginInfo);
            if ($result > 0){
                return Render::success('添加成功');
            }
            return Render::error('添加失败');
        } catch (\Exception $e) {
            return Render::error("系统异常，请稍后再试！");
        }
    }

    /**
     * 编辑新闻
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editShow($id){
        $detail = $this->newsService->getAdminNewsById($id);
        return view('admin.news.edit',['detail'=>$detail]);
    }

    /**
     * 修改新闻
     * @param NewsValidator $validator
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(NewsValidator $validator){
        $data = $validator->only(['id','news_title','news_cover','news_desc','sort','content']);
        //修改数据
        try {
            $result = $this->newsService->editNews($data, $this->loginInfo);
            if ($result > 0){
                return Render::success('修改成功');
            }
            return Render::error('修改失败');
        } catch (\Exception $e) {
            return Render::error("系统异常，请稍后再试！");
        }

    }

    /**
     * 批量删除
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delBatch(Request $request){
        $ids = $request->input('ids');
        if (empty($ids)){
            return Render::error('参数错误');
        }
        //删除数据
        try {
            $result = $this->newsService->delBatch($ids, $this->loginInfo);
            if ($result > 0){
                return Render::success('删除成功');
            }
            return Render::error('删除失败');
        } catch (\Exception $e) {
            return Render::error("系统异常，请稍后再试！");
        }
    }

    /**
     * 修改新闻状态
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(Request $request){
        $data = $request->only(['id','status']);
        try {
            $result = $this->newsService->updateStatus($data, $this->loginInfo);
            if ($result > 0){
                return  Render::success('操作成功');
            }
            return  Render::error('操作失败');
        } catch (\Exception $e) {
            return Render::error("系统异常，请稍后再试！");
        }

    }

    /**
     * 修改推荐状态
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateRecommend(Request $request){
        $data = $request->only(['id','is_recommend']);
        try {
            $result = $this->newsService->updateIsRecommend($data, $this->loginInfo);
            if ($result > 0){
                return Render::success('操作成功');
            }
            return Render::error('操作失败');
        } catch (\Exception $e) {
            return Render::error("系统异常，请稍后再试！");
        }
    }
}
