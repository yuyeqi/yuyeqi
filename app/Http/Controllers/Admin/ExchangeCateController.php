<?php

namespace App\Http\Controllers\Admin;

use App\Http\Service\ExchangeCateService;
use App\Http\Service\GoodsCateService;
use App\Library\Render;
use Illuminate\Http\Request;

/**
 * 兑换商品分类控制器
 * Class GoodsCateController
 * @package App\Http\Controllers\Admin
 */
class ExchangeCateController extends BaseController
{
    //兑换商品分类服务层
    private $exchangeCateService;

    /**
     * CasesController constructor.
     */
    public function __construct()
    {
        parent:: __construct();
        $this->exchangeCateService = isset($this->exchangeCateService) ?: new ExchangeCateService();
    }

    /**
     * 分类列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){
        return view('admin.exchangeCate.index');
    }
    /**
     * 分类列表
     * @param Request $request
     */
    public function getLists(Request $request){
        $keyword = trim($request->get('keywords',''));
        $limit = intval($request->get('limit','10'));
        $lists = $this->exchangeCateService->getLists($keyword,$limit);
        return Render::table($lists->items(),$lists->total());
    }

    /**
     * 添加
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addShow(){
        return view('admin.exchangeCate.add');
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
            $result = $this->exchangeCateService->add($data, $this->loginInfo);
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
        $detail = $this->exchangeCateService->getDetailById($id);
        return view('admin.exchangeCate.edit',['detail'=>$detail]);
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
            $result = $this->exchangeCateService->edit($data, $this->loginInfo);
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
            $result = $this->exchangeCateService->delBatch($ids, $this->loginInfo);
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
            $result = $this->exchangeCateService->updateStatus($data, $this->loginInfo);
            if ($result > 0){
                return  Render::success('操作成功');
            }
            return  Render::error('操作失败');
        } catch (\Exception $e) {
            return Render::error("系统异常，请稍后再试！");
        }

    }
}
