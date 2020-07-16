<?php


namespace App\Http\Controllers\Api\V1;

use App\Http\Service\UserService;
use App\Library\Render;
use Illuminate\Http\Request;

/**
 * 用户控制器
 * Class UserController
 * @package App\Http\Controllers\Api\V1
 */
class UserController extends BaseController
{
    private $userService;  //用户服务层

    /**
     * UserController constructor.
     */
    public function __construct()
    {
        $this->userService = isset($this->userService) ?: new UserService();
    }

    /**
     * 用户账户信息
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserAccount(){
        $wallet = $this->userService->getUserAccount($this->userInfo);
        return Render::success("获取成功",$wallet);
    }

    /**
     * 兑换现金
     * @return \Illuminate\Http\JsonResponse
     */
    public function exchangeCash(Request $request){
        $score = $request->get("score");
        try {
            if ($this->userService->exchangeCash($score,$this->userInfo)) {
                return Render::success("兑换成功");
            }
            return  Render::error("兑换失败");
        } catch (\Exception $e) {
            return  Render::error("系统异常，请稍后再试！");
        }

    }

}
