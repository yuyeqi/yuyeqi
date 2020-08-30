<?php


namespace App\Http\Service;


use App\Models\Cases;
use App\Models\Permission;

/**
 * 权限服务层
 * Class PermissionService
 * @package App\Http\Service
 */
class PermissionService extends BaseSerivce
{
    private $permission;

    /**
     * CasesService constructor.
     */
    public function __construct()
    {
        $this->permission = isset($this->permission) ?: new Permission();
    }

    /**
     * 权限列表
     * @param string $keyword
     * @param int $limit
     * @return mixed
     */
    public function getLists(string $keyword, int $limit)
    {
        return $this->permission->getLists($keyword,$limit);

    }
    /**
     * 添加
     * @param array $data
     * @param $loginInfo
     * @return mixed///
     */
    public function addPermission(array $data, $loginInfo)
    {
        $data['create_user_id'] = $loginInfo['id'];
        $data['create_user_name'] = $loginInfo['username'];
        $data['update_user_id'] = $loginInfo['id'];;
        $data['update_user_name'] = $loginInfo['username'];
        return $this->permission->addPermission($data);
    }

    /**
     * 修改
     * @param array $data
     * @param $loginInfo
     * @return mixed
     */
    public function edit(array $data, $loginInfo)
    {
        $data['update_user_id'] = $loginInfo['id'];;
        $data['update_user_name'] = $loginInfo['username'];
        return $this->permission->edit($data);
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
        return  $this->permission->delBatch($data,$ids);
    }

    /**
     * 获取下拉的权限的列表
     * @return mixed
     */
    public function getSelectLists(){
        return $this->permission->getSelectPermession();
    }


    /**
     * 角色选择权限
     * @return mixed
     */
    public function getPermissionLists(){
        return $this->permission->getPermissionLists();
    }

}
