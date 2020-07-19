<?php


namespace App\Http\Controllers\Api\V1;

/**
 * 个人中心
 * Class PublicController
 * @package App\Http\Controllers\Api\V1
 */
class ProfileController extends BaseController
{

    /**
     * 获取海报
     * @return int
     */
    public function getMyPostal(){
        $url = 'baidu.com';
        return 1;
    }

    /**
     * 关于我们
     * @return string
     */
    public function aboutUs(){
        return "2333";
    }
}
