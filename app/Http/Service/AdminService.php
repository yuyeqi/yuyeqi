<?php


namespace App\Http\Service;

use App\Models\Admin;

/**
 * 后台用户服务层
 * Class AdminService
 * @package App\Http\Service
 */
class AdminService
{
    //用户模型对象
    private $admin;
    /**
     * AdminService constructor.
     */
    public function __construct()
    {
        $this->admin = isset($this->admin) ?: new Admin();
    }

    /**
     * 后台用户列表
     * @param $search
     */
    public function getAdminLists($keyword,$limit){
        return $this->admin->getAdminLists($keyword,$limit);
    }

    public function addAdmin($data){
        $data['create_user_id'] = 1;
        $data['create_user_name'] = '朱永利';
        $data['update_id'] = 1;
        $data['update_user_name'] = '朱永利';
        return $this->admin->addAdmin($data);
    }
}
