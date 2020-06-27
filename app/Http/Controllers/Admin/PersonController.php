<?php

namespace App\Http\Controllers\Admin;

use App\Http\Service\PersonCateService;
use App\Http\Service\PersonService;
use App\Library\Render;
use Illuminate\Http\Request;

/**
 * 后台私人定制控制器
 * Class NewsController
 * @package App\Http\Controllers\Admin
 */
class PersonController extends BaseController
{
    //私人定制服务层
    private $personService;
    //私人定制分离
    private $personCateService;

    /**
     * PersonController constructor.
     */
    public function __construct()
    {
        parent:: __construct();
        $this->personService = isset($this->personService) ?: new PersonService();
        $this->personCateService = isset($this->personCateService) ?: new PersonCateService();
    }

    /**
     * 私人定制列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){
        $cateLists = $this->personCateService->getPersonCateSelectLists();
        return view('admin.person.index',['cateLists'=>$cateLists]);
    }
    /**
     * 私人定制列表
     * @param Request $request
     */
    public function getPersonLists(Request $request){
        //接收参数
        $keywords = $request->get('keywords','');
        $cateId = $request->get('cateId','0');
        $startTime = $request->get('startTime','');
        $endTime = $request->get('endTime','');
        $limit = intval($request->get('limit','10'));
        $lists = $this->personService->getPersonLists( $keywords, $cateId,$startTime,$endTime,$limit);
        return Render::table($lists->items(),$lists->total());
    }

    /**
     * 编辑
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editShow($id){
        $detail = $this->personService->getAdminPersonById($id);
        return view('admin.person.edit',['detail'=>$detail]);
    }

    /**
     * 修改
     * @param NewsValidator $validator
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Request $request){
        $data = $request->only(['id','person_name','phone','company','ocupation','person_remark','person_price','sales_price']);
        //修改数据
        try {
            $result = $this->personService->editPerson($data, $this->loginInfo);
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
            $result = $this->personService->delBatch($ids, $this->loginInfo);
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
        $data = $request->only(['id','is_audit','audit_remark']);
        try {
            $result = $this->personService->updateStatus($data, $this->loginInfo);
            if ($result > 0){
                return  Render::success('操作成功');
            }
            return  Render::error('操作失败');
        } catch (\Exception $e) {
            return  Render::error($e->getMessage());
        }

    }

    /**
     * 审核
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function auditShow($id){
        return view('admin.person.audit',['id'=>$id]);
    }
}
