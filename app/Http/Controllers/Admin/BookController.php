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
            $result = $this->bookService->delBatch($ids, $this->loginInfo);
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
        $data = $request->only(['id','status','audit_remark']);
        $result = $this->bookService->updateStatus($data, $this->loginInfo);
        if ($result > 0){
            return  Render::success('操作成功');
        }
        return  Render::error($this->bookService->getErrorMsg() ?: '操作失败');

    }

    /**
     * 审核页面
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function audit($id){
        return view('admin.book.audit',compact('id'));
    }
}
