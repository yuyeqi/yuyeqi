<?php

namespace App\Http\Controllers\Admin;

use App\Http\Service\SlideshowService;
use App\Library\Render;
use Illuminate\Http\Request;

/**
 * 后台轮播控制器
 * Class SlideshowController
 * @package App\Http\Controllers\Admin
 */
class SlideshowController extends BaseController
{
    //轮播服务层
    private $slideshowService;

    /**
     * SlideshowController constructor.
     */
    public function __construct()
    {
        parent:: __construct();
        $this->slideshowService = isset($this->slideshowService) ?: new SlideshowService();
    }

    /**
     * 轮播列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){
        return view('admin.slideshow.index');
    }
    /**
     * 轮播列表
     * @param Request $request
     */
    public function getSlideshowLists(Request $request){
        //接收参数
        $keyword = trim($request->get('keywords',''));
        $limit = intval($request->get('limit','10'));
        $lists = $this->slideshowService->getSlideshowAdminLists($keyword,$limit);
        return Render::table($lists->items(),$lists->total());
    }

    /**
     * 添加新闻
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addShow(){
        return view('admin.slideshow.add');
    }

    /**
     * 添加数据
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request){
        $data = $request->only(['slideshow_name','slideshow_url','sort','description']);
        //添加数据
        try {
            $result = $this->slideshowService->addSlideshow($data, $this->loginInfo);
            if (!empty($result)){
                return Render::success('添加成功');
            }
            return Render::error('添加失败');
        } catch (\Exception $e) {
            return Render::error("系统异常，请稍后再试！");
        }
    }

    /**
     * 编辑
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editShow($id){
        $detail = $this->slideshowService->getAdminSlideshowById($id);
        return view('admin.slideshow.edit',['detail'=>$detail]);
    }

    /**
     * 修改
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Request $request){
        $data = $request->only(['id','slideshow_name','slideshow_url','sort','description']);
        //修改数据
        try {
            $result = $this->slideshowService->editSlideshow($data, $this->loginInfo);
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
            $result = $this->slideshowService->delBatch($ids, $this->loginInfo);
            if ($result > 0){
                return Render::success('删除成功');
            }
            return Render::error('删除失败');
        } catch (\Exception $e) {
            return Render::error("系统异常，请稍后再试！");
        }
    }

    /**
     * 修改状态
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(Request $request){
        $data = $request->only(['id','status']);
        try {
            $result = $this->slideshowService->updateStatus($data, $this->loginInfo);
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
