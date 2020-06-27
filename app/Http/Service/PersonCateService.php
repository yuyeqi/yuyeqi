<?php


namespace App\Http\Service;


use App\Models\PersonCate;

/**
 * 私人定制分类服务层
 * Class PersonCateService
 * @package App\Http\Service
 */
class PersonCateService extends BaseSerivce
{
    private $personCate;

    /**
     * PersonCateService constructor.
     */
    public function __construct()
    {
        $this->personCate = isset($this->personCate) ?: new PersonCate();
    }

    /**
     * 私人定制分类列表
     * @return mixed
     */
    public function getPersonCateLists()
    {
        return $this->personCate->getPersonCateLists();
    }

    /**
     * 私人定制分类列表
     * @param string $keyword
     * @param int $limit
     * @return mixed
     */
    public function getPersonCateAdminLists(string $keyword, int $limit)
    {
        return $this->personCate->getPersonCateAdminLists($keyword,$limit);
    }

    /**
     * 添加分类
     * @param array $data
     * @param $loginInfo
     * @return mixed
     */
    public function addPersonCate(array $data, $loginInfo)
    {
        $data['create_user_id'] = $loginInfo['id'];
        $data['create_user_name'] = $loginInfo['username'];
        $data['update_user_id'] = $loginInfo['id'];;
        $data['update_user_name'] = $loginInfo['username'];
        return $this->personCate->addPersonCate($data);
    }

    /**
     * 分类 详情
     * @param $id
     * @return mixed
     */
    public function getAdminCateById($id)
    {
        return $this->personCate->getPersonCateById($id);
    }

    /**
     * 修改分类
     * @param array $data
     * @param $loginInfo
     * @return mixed
     */
    public function editPersonCate(array $data, $loginInfo)
    {
        $data['update_user_id'] = $loginInfo['id'];;
        $data['update_user_name'] = $loginInfo['username'];
        return $this->personCate->editPersonCate($data);
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
        return  $this->personCate->delBatch($data,$ids);
    }

    /**
     * 修改状态
     * @param array $data
     * @param $loginInfo
     * @return mixed
     */
    public function updateStatus(array $data, $loginInfo)
    {
        $data['update_user_id'] = $loginInfo['id'];;
        $data['update_user_name'] = $loginInfo['username'];
        return $this->personCate->updateStatus($data);
    }

    /**
     * 后端下拉列表
     * @return mixed
     */
    public function getPersonCateSelectLists()
    {
        return $this->personCate->getPersonCateSelectLists();
    }

}
