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
use Illuminate\Support\Facades\Storage;


/**
 * 公共接口
 * Class PublicController
 * @package App\Http\Controllers\Api\V1
 */
class PublicController extends BaseController
{
    private $userService;  //用户服务层
    private $userCateService;   //用户分类服务层
    const   PHTHURL = 'api'; //上传文件路径

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
        $file = $request->file('file');
        if ($file && $file->isValid()) {
            // 获取文件相关信息
            $ext = $file->getClientOriginalExtension();     // 扩展名
            $realPath = $file->getRealPath();   //临时文件的绝对路径
            $type = $file->getClientMimeType();     // image/jpeg
            $size =$file->getSize();
            if($size > 4*1024*1024){
                return Render::error('文件大小超过4M');
            }
            $extArr = array('jpg','jpeg','png','gif');
            if(!in_array($ext,$extArr)){
                return Render::error('文件格式不正确');
            }
            $bool = Storage::put(self::PHTHURL, $file);
            if ($bool){
                $url = Storage::url($bool);
                return Render::success('上传成功',$url);
            }else{
                return  Render::error('上传失败');
            }
        }
        return  Render::error('上传失败');

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
