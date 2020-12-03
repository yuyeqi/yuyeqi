<?php


namespace App\Http\Service;

use App\Models\Admin;
use App\Models\AdminRole;
use Illuminate\Support\Facades\DB;

/**
 * 后台用户服务层
 * Class AdminService
 * @package App\Http\Service
 */
class AdminService extends BaseSerivce
{
    //用户模型对象
    private $admin;
    //用户角色模型
    private $adminRole;
    /**
     * AdminService constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->admin = isset($this->admin) ?: new Admin();
        $this->adminRole = isset($this->adminRole) ?: new AdminRole();
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
    public function addAdmin($data,$loginInfo){
        $data['create_user_id'] = $loginInfo['id'];
        $data['create_user_name'] = $loginInfo['username'];
        $data['update_user_id'] = $loginInfo['id'];;
        $data['update_user_name'] = $loginInfo['username'];
        $data['password'] = bcrypt($data['password']);
        $ids = $data['ids'];
        unset($data['ids']);
        //1.开启事务
        DB::beginTransaction();
        try {
            //2.添加后台用户
            $admin = $this->admin->addAdmin($data);
            //3.添加用户角色
            $adminRole = [];
            if (!empty($admin) && count($ids) > 0){
                foreach ($ids as $item){
                    $adminRole[] = [
                        'admin_id' => $admin->id,
                        'role_id' => $item
                    ];
                }
                AdminRole::insert($adminRole);
            }
            DB::commit();
            return  true;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->setErrorMsg($e->getMessage());
            return  false;
        }
    }

    /**
     * 后台用户详情
     * @param $id
     * @return mixed
     */
    public function getAdminDetail($id){
        return $this->admin->getAdminDetail($id);
    }

    /**
     * 更新用户信息
     * @param $data
     * @return mixed
     */
    public function updateAdmin($data, $loginInfo)
    {
        $data['update_user_id'] = $loginInfo['id'];
        $data['update_user_name'] = $loginInfo['username'];
        $ids = $data['ids'];
        unset($data['ids']);
        //1.开启事务
        DB::beginTransaction();
        try {
            //2.添加后台用户
            $this->admin->updateAdmin($data);
            //3.删除角色
            $this->adminRole->deletAminRole($data['id']);
            //4.添加用户角色
            $adminRole = [];
            if (!empty($data) && count($ids) > 0) {
                foreach ($ids as $item) {
                    $adminRole[] = [
                        'admin_id' => $data['id'],
                        'role_id' => $item
                    ];
                }
                AdminRole::insert($adminRole);
            }
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->setErrorMsg($e->getMessage());
            return false;
        }
    }

    /**
     * 更新密码
     * @param $data
     * @return bool
     */
    public function updatePwd($data,$loginInfo){
        $data['update_user_id'] = $loginInfo['id'];
        $data['update_user_name'] = $loginInfo['username'];
        $data['password'] = bcrypt($data['password']);
        return $this->admin->updateAdmin($data);
    }

    /**
     * 删除用户
     * @param $id
     * @return mixed
     */
    public function deleteAdmin($id,$loginInfo){
        $data['id'] = $id;
        $data['update_user_id'] = $loginInfo['id'];
        $data['update_user_name'] = $loginInfo['username'];
        $data['is_delete'] = 1;
        if ($id == 1){
            $this->setErrorMsg('禁止删除超级管理员');
            return  false;
        }
        return $this->admin->updateAdmin($data);
    }

    /**
     * 修改用户状态
     * @param $data
     * @return bool
     */
    public function updateStatus($data,$loginInfo){
        $data['update_user_id'] = $loginInfo['id'];
        $data['update_user_name'] = $loginInfo['username'];
        return $this->admin->updateAdmin($data);
    }

    /**
     * 批量删除
     * @param $ids
     * @return mixed
     */
    public function deleteAll($ids,$loginInfo){
        $data = [];
        $data['update_user_id'] = $loginInfo['id'];
        $data['update_user_name'] = $loginInfo['username'];
        $data['is_delete'] = 1;
        if (in_array(1,$ids)){
            $this->setErrorMsg('禁止删除超级管理员');
            return  false;
        }
        return $this->admin->deleteAll($ids,$data);
    }

    /**
     * 通过账户获取用户信息
     * @param array $data
     */
    public function getAdminByAcount($account)
    {
        return $this->admin->getAdminByAcount($account);
    }
}
