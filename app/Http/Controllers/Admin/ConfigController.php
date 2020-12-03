<?php

namespace App\Http\Controllers\Admin;

use App\Http\Service\ConfigService;
use App\Library\Render;
use App\Models\Config;
use Illuminate\Http\Request;

/**
 * 配置控制器
 * Class ConfigController
 * @package App\Http\Controllers\Admin
 */
class ConfigController extends BaseController
{
    //新闻服务层
    private $configService;

    /**
     * ConfigController constructor.
     */
    public function __construct()
    {
        parent:: __construct();
        $this->configService = isset($this->configService) ?: new ConfigService();
    }

    /**
     * 配置列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){
        return view('admin.config.index');
    }
    /**
     * 新闻列表
     * @param Request $request
     */
    public function getConfigLists(Request $request){
        //接收参数
        $keyword = trim($request->get('keywords',''));
        $limit = intval($request->get('limit','10'));
        $lists = $this->configService->getConfigLists($keyword,$limit);
        return Render::table($lists->items(),$lists->total());
    }


    /**
     * 编辑
     * @param $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editShow($configNo){
        if (empty($configNo)){
            return  Render::error('参数错误');
        }
        $detail = Config::getConfigDetail($configNo);
        return view('admin.config.edit',['detail'=>$detail]);
    }

    /**
     * 修改新闻
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Request $request){
        $data = $request->only(['config_no','config_name','background','config_value','content']);
        //修改数据
        try {
            $result = $this->configService->edit($data, $this->loginInfo);
            if ($result > 0){
                return Render::success('修改成功');
            }
            return Render::error('修改失败');
        } catch (\Exception $e) {
            return Render::error("系统异常，请稍后再试！");
        }

    }
}
