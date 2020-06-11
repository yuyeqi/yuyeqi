<?php


namespace App\Http\Service;

use App\Models\Admin;
use Illuminate\Support\Facades\Crypt;

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

    /**
     * 添加用户
     * @param $data
     * @return bool
     */
    public function addAdmin($data){
        $data['create_user_id'] = 1;
        $data['create_user_name'] = '朱永利';
        $data['update_id'] = 1;
        $data['update_user_name'] = '朱永利';
        $data['password'] = Crypt::encrypt($data['password']);
        return $this->admin->addAdmin($data);
    }

    /**
     * 后台用户详情
     * @param $id
     * @return mixed
     */
    public function getAdminDetail($id){
        return $this->admin->getAdminDetail($id);
    }
}
