<?php


namespace App\Http\Service;


use App\Models\User;

/**
 * 用户服务层
 * Class SlideshowService
 * @package App\Http\Service
 */
class UserService extends BaseSerivce
{
    private $user;

    /**
     * SlideshowService constructor.
     */
    public function __construct()
    {
        $this->user = isset($this->user) ?: new User();
    }

    /**
     * 小程序用户信息
     * @return mixed
     */
    public function getUserInfo($id){
        return $this->user->getUserInfo($id);
    }

}
