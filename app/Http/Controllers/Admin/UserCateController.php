<?php

namespace App\Http\Controllers\Admin;

use App\Http\Service\UserCateService;
use App\Library\Render;
use Illuminate\Http\Request;

/**
 * 用户分类控制器
 * Class UserCateController
 * @package App\Http\Controllers\Admin
 */
class UserCateController extends BaseController
{
    //用户分类服务层
    private $userCateService;

    /**
     * UserCateController constructor.
     */
    public function __construct()
    {
        parent:: __construct();
        $this->userCateService = isset($this->userCateService) ?: new UserCateService();
    }

    /**
     * 用户分类列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){
        return view('admin.userCate.index');
    }
    /**
     * 用户分类列表
     * @param Request $request
     */
    public function getLists(Request $request){
        //接收参数
        $keywords = $request->get('keywords','');
        $limit = intval($request->get('limit','10'));
        $lists = $this->userCateService->getLists( $keywords,$limit);
        return Render::table($lists->items(),$lists->total());
    }

    /**
     * 编辑
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editShow($id){
        $detail = $this->userCateService->getDetail($id);
        return view('admin.userCate.edit',['detail'=>$detail]);
    }

    /**
     * 添加展示
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addShow(){
        return view('admin.userCate.add');
    }

    /**
     * 添加数据
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request){
        $data = $request->only(['cate_name','bg_images','register_account','tg_account','book_score','store_score','order_score']);
        //添加数据
        try {
            if ($this->userCateService->add($data, $this->loginInfo)){
                return Render::success('添加成功');
            }
            return Render::error('添加失败');
        } catch (\Exception $e) {
            return Render::error("系统异常，请稍后再试！");
        }
    }
    /**
     * 修改
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Request $request){
        $data = $request->only(['id','cate_name','bg_images','register_account','tg_account','book_score','store_score','order_score']);
        //修改数据
        try {
            $result = $this->userCateService->edit($data, $this->loginInfo);
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
            $result = $this->userCateService->delBatch($ids, $this->loginInfo);
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
            $result = $this->userCateService->updateStatus($data, $this->loginInfo);
            if ($result > 0){
                return  Render::success('操作成功');
            }
            return  Render::error('操作失败');
        } catch (\Exception $e) {
            return Render::error("系统异常，请稍后再试！");
        }

    }
}
