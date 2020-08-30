<?php


namespace App\Http\Service;


use App\Models\AdminRole;
use App\Models\Role;
use App\Models\RolePermission;
use Illuminate\Support\Facades\DB;

/**
 * 角色服务层
 * Class CasesService
 * @package App\Http\Service
 */
class RoleService extends BaseSerivce
{
    //角色模型
    private $role;

    //角色权限模型
    private $rolePermission;

    //用户角色模型
    private $adminRole;

    //权限服务层
    private $permissionService;
    /**
     * CasesService constructor.
     */
    public function __construct()
    {
        $this->role = isset($this->role) ?: new Role();
        $this->rolePermission = isset($this->rolePermission) ?: new RolePermission();
        $this->adminRole = isset($this->adminRole) ?: new AdminRole();
        $this->permissionService = isset($this->permissionService) ?: new PermissionService();
    }

    /**
     * 案例列表
     * @param string $keyword
     * @param int $limit
     * @return mixed
     */
    public function getLists(int $limit)
    {
        return $this->role->getList($limit);

    }
    /**
     * 添加
     * @param array $data
     * @param $loginInfo
     * @return mixed///
     */
    public function addCases(array $data, $loginInfo)
    {
        $data['create_user_id'] = $loginInfo['id'];
        $data['create_user_name'] = $loginInfo['username'];
        $data['update_user_id'] = $loginInfo['id'];;
        $data['update_user_name'] = $loginInfo['username'];
        return $this->cases->addCases($data);
    }

    /**
     * 修改
     * @param array $data
     * @param $loginInfo
     * @return mixed
     */
    public function editCases(array $data, $loginInfo)
    {
        $data['update_user_id'] = $loginInfo['id'];;
        $data['update_user_name'] = $loginInfo['username'];
        return $this->cases->editCases($data);
    }

    /**
     * 后端新闻详情
     * @param $id
     * @return mixed
     */
    public  function getAdminCasesById($id){
        return Cases::getCasesDetail($id);
    }

    /**
     * 删除
     * @param array $ids
     * @param $loginInfo
     * @return mixed
     */
    public function delBatch(array $ids,$loginInfo)
    {
        $data['update_user_id'] = $loginInfo['id'];;
        $data['update_user_name'] = $loginInfo['username'];
        $data['is_delete'] = 1;
        return  $this->cases->delBatch($data,$ids);
    }

    /**
     * 添加角色
     * @param $data
     * @param $loginInfo
     * @return bool
     */
    public function addRole($data,$loginInfo){
        $data['create_user_id'] = $loginInfo['id'];
        $data['create_user_name'] = $loginInfo['username'];
        $data['update_user_id'] = $loginInfo['id'];;
        $data['update_user_name'] = $loginInfo['username'];
        //1.添加角色
        $ids = $data['ids'];
        unset($data['ids']);
        //2.开启事务
        DB::beginTransaction();
        try {
            //3.添加角色
            $role = Role::create($data);
            //4.添加角色权限对应关系
            $roleToPermission = [];
            if (count($ids) > 0 && $role->id > 0){
                foreach ($ids as $item){
                    $roleToPermission[] = [
                        'role_id' => $role->id,
                        'permission_id' => $item
                    ];
                }
                RolePermission::insert($roleToPermission);
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
     * 角色权限模型
     * @param $id
     * @return mixed
     */
    public function getRolePermission($id){
        return $this->rolePermission->getRolePermission($id);
    }

    public function editRole($data,$loginInfo){
        $data['update_user_id'] = $loginInfo['id'];;
        $data['update_user_name'] = $loginInfo['username'];
        //1.修改角色
        $ids = $data['ids'];
        unset($data['ids']);
        //2.开启事务
        DB::beginTransaction();
        try {
            $this->role->updateRole($data);
            //3.删除权限
            $this->rolePermission->deletePermission($data['id']);
            //4.添加角色权限对应关系
            $roleToPermission = [];
            if (count($ids) > 0 && $data['id'] > 0){
                foreach ($ids as $item){
                    $roleToPermission[] = [
                        'role_id' => $data['id'],
                        'permission_id' => $item
                    ];
                }
                RolePermission::insert($roleToPermission);
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
     * 角色列表
     * @return mixed
     */
    public function getRolesLists(){
        return $this->role->getRolesLists();
    }

    /**
     * 用户角色模型
     * @param $adminId
     * @return mixed
     */
    public function getAdminRoles($adminId){
        return $this->adminRole->getAdminRoles($adminId);
    }

    /**
     * 菜单列表
     * @param $adminId
     * @return mixed
     */
    public function getMeanLists($adminId){
        //1.超管直接进
        if($adminId == 1){
           return $this->permissionService->getPermissionLists();
        }
        return $this->adminRole->getMeanLists($adminId);
    }
}
