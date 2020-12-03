<?php

namespace App\Http\Controllers\Admin;

use App\Http\Service\PersonCateService;
use App\Library\Render;
use Illuminate\Http\Request;

/**
 * 后台私人定制分类控制器
 * Class PersonCateController
 * @package App\Http\Controllers\Admin
 */
class PersonCateController extends BaseController
{
    //私人定制分类服务层
    private $personCateService;

    /**
     * PersonCateController constructor.
     */
    public function __construct()
    {
        parent:: __construct();
        $this->personCateService = isset($this->personCateService) ?: new PersonCateService();
    }

    /**
     * 私人定制分类列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){
        return view('admin.personCate.index');
    }
    /**
     * 私人定制列表
     * @param Request $request
     */
    public function getPersonCateLists(Request $request){
        //接收参数
        $keyword = trim($request->get('keywords',''));
        $limit = intval($request->get('limit','10'));
        $lists = $this->personCateService->getPersonCateAdminLists($keyword,$limit);
        return Render::table($lists->items(),$lists->total());
    }
    /**
     * 添加新闻
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addShow(){
        return view('admin.personCate.add');
    }

    /**
     * 添加数据
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request){
        $data = $request->only(['cate_name','bg_url','sort']);
        //添加数据
        try {
            $result = $this->personCateService->addPersonCate($data, $this->loginInfo);
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
        $detail = $this->personCateService->getAdminCateById($id);
        return view('admin.personCate.edit',['detail'=>$detail]);
    }

    /**
     * 修改
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Request $request){
        $data = $request->only(['id','cate_name','bg_url','sort']);
        //修改数据
        try {
            $result = $this->personCateService->editPersonCate($data, $this->loginInfo);
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
            $result = $this->personCateService->delBatch($ids, $this->loginInfo);
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
            $result = $this->personCateService->updateStatus($data, $this->loginInfo);
            if ($result > 0){
                return  Render::success('操作成功');
            }
            return  Render::error('操作失败');
        } catch (\Exception $e) {
            return Render::error("系统异常，请稍后再试！");
        }

    }

}
