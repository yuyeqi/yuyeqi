<?php

namespace App\Http\Controllers\Admin;

use App\Http\Service\BookService;
use App\Library\Render;
use Illuminate\Http\Request;

/**
 * 后台报备控制器
 * Class BookController
 * @package App\Http\Controllers\Admin
 */
class BookController extends BaseController
{
    //轮播服务层
    private $bookService;

    /**
     * BookController constructor.
     */
    public function __construct()
    {
        parent:: __construct();
        $this->bookService = isset($this->bookService) ?: new BookService();
    }

    /**
     * 报备列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){
        return view('admin.book.index');
    }
    /**
     * 报备列表
     * @param Request $request
     */
    public function getBookAdminLists(Request $request){
        //接收参数
        $data = $request->only(['book_no','client_name','endTime','community','house_name',
            'start_time','end_time','deal_start_time','deal_end_time','user_name','status']);
        $limit = intval($request->get('limit','10'));
        $lists = $this->bookService->getBookAdminLists($data,$limit);
        return Render::table($lists->items(),$lists->total());;
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
            return Render::error($e->getMessage());
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
            return Render::error($e->getMessage());
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
            return Render::error($e->getMessage());
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
            return  Render::error($e->getMessage());
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
            return  Render::error($e->getMessage());
        }
    }
}
