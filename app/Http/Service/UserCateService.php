<?php


namespace App\Http\Service;


use App\Models\UserCate;

/**
 * 用户分类服务层
 * Class UserCateService
 * @package App\Http\Service
 */
class UserCateService extends BaseSerivce
{
    private $userCate;

    /**
     * UserCateService constructor.
     */
    public function __construct()
    {
        $this->userCate = isset($this->userCate) ?: new UserCate();
    }

    /**
     * 用户分类列表
     * @param $keywords
     * @param $limit
     * @return mixed
     */
    public function getLists($keywords,$limit){
        return $this->userCate->getLists($keywords,$limit);
    }

    /**
     * 用户分类详情
     * @param $id
     * @return mixed
     */
    public function getDetail($id){
        return $this->userCate->getDetail($id);
    }
    /**
     * 添加用户分类
     * @param $data
     * @param $loginInfo
     * @return mixed
     */
    public function add($data,$loginInfo){
        $data['create_user_id'] = $loginInfo['id'];
        $data['create_user_name'] = $loginInfo['username'];
        $data['update_user_id'] = $loginInfo['id'];;
        $data['update_user_name'] = $loginInfo['username'];
        return $this->userCate->add($data);
    }

    /**修改用户分离
     * @param $data
     * @param $loginInfo
     * @return mixed
     */
    public function edit($data,$loginInfo){
        $data['update_user_id'] = $loginInfo['id'];;
        $data['update_user_name'] = $loginInfo['username'];
        return $this->userCate->edit($data);
    }

    /**
     * 批量删除
     * @param $ids
     * @param $loginInfo
     * @return mixed
     */
    public function delBatch($ids,$loginInfo){
        $data['update_user_id'] = $loginInfo['id'];;
        $data['update_user_name'] = $loginInfo['username'];
        $data['is_delete'] = 1;
        return $this->userCate->delBitch($ids,$data);
    }

    /**
     * 更新用户分类状态
     * @param $data
     * @param $loginInfo
     * @return mixed
     */
    public function updateStatus($data,$loginInfo){
        $data['update_user_id'] = $loginInfo['id'];;
        $data['update_user_name'] = $loginInfo['username'];
        return $this->userCate->updateStatus($data);
    }

}
