<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\CasesValidator;
use App\Http\Service\CasesService;
use App\Http\Service\GoodsCateService;
use App\Library\Render;
use Illuminate\Http\Request;

/**
 * 商品分类控制器
 * Class GoodsCateController
 * @package App\Http\Controllers\Admin
 */
class GoodsCateController extends BaseController
{
    //商品分类服务层
    private $goodsCateService;

    /**
     * CasesController constructor.
     */
    public function __construct()
    {
        parent:: __construct();
        $this->goodsCateService = isset($this->goodsCateService) ?: new GoodsCateService();
    }

    /**
     * 分类列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){
        return view('admin.goodsCate.index');
    }
    /**
     * 分类列表
     * @param Request $request
     */
    public function getLists(Request $request){
        $keyword = trim($request->get('keywords',''));
        $limit = intval($request->get('limit','10'));
        $lists = $this->goodsCateService->getLists($keyword,$limit);
        return Render::table($lists->items(),$lists->total());
    }

    /**
     * 添加
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addShow(){
        return view('admin.goodsCate.add');
    }

    /**
     * 添加数据
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request){
        $data = $request->only(['cate_name','sort']);
        //添加数据
        try {
            $result = $this->goodsCateService->add($data, $this->loginInfo);
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
        $detail = $this->goodsCateService->getDetailById($id);
        return view('admin.goodsCate.edit',['detail'=>$detail]);
    }

    /**
     * 修改
     * @param NewsValidator $validator
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Request $request){
        $data = $request->only(['id','cate_name','sort']);
        //修改数据
        try {
            $result = $this->goodsCateService->edit($data, $this->loginInfo);
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
            $result = $this->goodsCateService->delBatch($ids, $this->loginInfo);
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
            $result = $this->goodsCateService->updateStatus($data, $this->loginInfo);
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
            return  Render::error($e->getMessage());
        }
    }
}
