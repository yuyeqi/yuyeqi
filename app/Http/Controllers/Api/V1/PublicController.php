<?php


namespace App\Http\Controllers\Api\V1;


use App\Http\Requests\Api\UserValidator;
use App\Http\Service\UserCateService;
use App\Http\Service\UserService;
use App\Library\Render;
use App\Models\Base;
use App\Models\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


/**
 * 公共接口
 * Class PublicController
 * @package App\Http\Controllers\Api\V1
 */
class PublicController extends BaseController
{
    private $userService;  //用户服务层
    private $userCateService;   //用户分类服务层

    /**
     * UserController constructor.
     */
    public function __construct()
    {
        parent:: __construct();
        $this->userService = isset($this->userService) ?: new UserService();
        $this->userCateService = isset($this->userCateService) ?: new UserCateService();
    }

    /**
     * 上传图片
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadImg(Request $request)
    {
        $Path = "/public/upload/";
        if (!empty($_FILES['file'])) {
            //获取扩展名
            $exename = $this->getExeName($_FILES['file']['name']);
            if ($exename != 'png' && $exename != 'jpg' && $exename != 'gif') {
                return Render::error("图片格式错误");
            }
            $fileName = $_SERVER['DOCUMENT_ROOT'] . $Path . date('Ym');//文件路径
            $upload_name = '/img_' . date("YmdHis") . rand(0, 100) . '.' . $exename;//文件名加后缀
            if (!file_exists($fileName)) {
                //进行文件创建
                mkdir($fileName, 0777, true);
            }
            $imageSavePath = $fileName . $upload_name;
            if (move_uploaded_file($_FILES['file']['tmp_name'], $imageSavePath)) {
                return Render::success('上传成功', $Path . date('Ym') . $upload_name);
            }
        } else {
            return Render::error("上传失败");
        }

    }

    /**
     * 获取文件名后缀
     * @param $fileName
     * @return string
     */
    public function getExeName($fileName)
    {
        $pathinfo = pathinfo($fileName);
        return strtolower($pathinfo['extension']);
    }

    /**
     * 获取配置信息
     * @param $configNo
     * @return \Illuminate\Http\JsonResponse
     */
    public function getConfigInfo($configNo)
    {
        $detail = Config::getConfigByNo($configNo);
        return Render::success("获取成功", $detail);
    }

    /**用户分类
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserCateLists()
    {
        $lists = $this->userCateService->getUserCateLists();
        return Render::success("获取成功", $lists);
    }

    /**
     * 用户注册
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(UserValidator $request)
    {
        $data = $request->only(['id', 'user_type', 'user_name', 'phone', 'sex', 'position_name', 'org_name', 'birthday', 'user_brand',
            'province', 'city', 'area', 'address', 'parent_id', 'share_type']);
        if ($this->userService->register($data)) {
            Log::info('【用户注册】----注册成功');
            return Render::success("注册成功，待审核");
        }
        return Render::error($this->userService->getErrorMsg() ?: "注册失败，请重试");
    }

    /**
     * 关于我们
     * @return \Illuminate\Http\JsonResponse
     */
    public function aboutUser()
    {
        $detail = Config::getConfigByNo('aboutUs');
        return Render::success('获取成功', $detail);
    }
}
