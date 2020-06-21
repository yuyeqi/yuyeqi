<?php


namespace App\Http\Controllers\Api\V1;


use App\Http\Service\SlideshowService;
use App\Http\Service\UserService;
use App\Library\Render;

/**
 * 首页控制器
 * Class IndexController
 * @package App\Http\Controllers\Api
 */
class IndexController extends BaseController
{
    //轮播
    private $slideshowService;
    //用户
    private $userService;

    /**
     * IndexController constructor.
     */
    public function __construct()
    {
        $this->slideshowService = isset($this->slideshowService) ?: new SlideshowService();
        $this->userService = isset($this->userService) ?: new UserService();
    }

    /**
     * 小程序获取轮播图
     */
    public function getSlideShowLists(){
        $data = $this->slideshowService->getSlideshowList();
        return Render::success('获取成功',$data);
    }

    /**
     * 小程序获取用户信息
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserInfo(){
        $userinfo = $this->userService->getUserInfo();
        $welcomMsg = '欢迎你进入黄派门窗';
        $data['userinfo'] = $userinfo;
        $data['welcomeMsg'] = $welcomMsg;
        return Render::success('获取成功',$data);
    }
}
