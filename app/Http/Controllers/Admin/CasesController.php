<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\CasesValidator;
use App\Http\Service\CasesService;
use App\Library\Render;
use Illuminate\Http\Request;

/**
 * 后台案例控制器
 * Class CasesController
 * @package App\Http\Controllers\Admin
 */
class CasesController extends BaseController
{
    //新闻服务层
    private $casesService;

    /**
     * CasesController constructor.
     */
    public function __construct()
    {
        parent:: __construct();
        $this->casesService = isset($this->casesService) ?: new CasesService();
    }

    /**
     * 案例列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){
        return view('admin.cases.index');
    }
    /**
     * 案例列表
     * @param Request $request
     */
    public function getNewsLists(Request $request){
        //接收参数
        $keyword = trim($request->get('keywords',''));
        $limit = intval($request->get('limit','10'));
        $lists = $this->casesService->getCasesAdminLists($keyword,$limit);
        return Render::table($lists->items(),$lists->total());
    }

    /**
     * 添加新闻
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addShow(){
        return view('admin.cases.add');
    }

    /**
     * 添加数据
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(CasesValidator $request){
        $data = $request->only(['case_name','case_desc','case_cover','sort','content']);
        //添加数据
        try {
            $result = $this->casesService->addCases($data, $this->loginInfo);
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
        $detail = $this->casesService->getAdminCasesById($id);
        return view('admin.cases.edit',['detail'=>$detail]);
    }

    /**
     * 修改
     * @param NewsValidator $validator
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(CasesValidator $validator){
        $data = $validator->only(['id','case_name','case_desc','case_cover','sort','content']);
        //修改数据
        try {
            $result = $this->casesService->editCases($data, $this->loginInfo);
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
            $result = $this->casesService->delBatch($ids, $this->loginInfo);
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
            $result = $this->casesService->updateStatus($data, $this->loginInfo);
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
