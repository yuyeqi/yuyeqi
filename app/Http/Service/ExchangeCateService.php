<?php


namespace App\Http\Service;

use App\Models\ExchangeCate;

/**
 * 兑换商品分类服务层
 * Class GoodsCateService
 * @package App\Http\Service
 */
class ExchangeCateService extends BaseSerivce
{
    private $exchangeCate;

    /**
     * GoodsCateService constructor.
     */
    public function __construct()
    {
        $this->exchangeCate = isset($this->exchangeCate) ?: new ExchangeCate();
    }

    /**
     * 兑换商品分类列表
     * @return int
     */
    public function getLists($keywords,$limit){
        return $this->exchangeCate->getLists($keywords,$limit);
    }

    /**
     * 添加
     * @param array $data
     * @param $loginInfo
     * @return mixed
     */
    public function add(array $data, $loginInfo)
    {
        $data['create_user_id'] = $loginInfo['id'];
        $data['create_user_name'] = $loginInfo['username'];
        $data['update_user_id'] = $loginInfo['id'];;
        $data['update_user_name'] = $loginInfo['username'];
        return $this->exchangeCate->add($data);
    }

    /**
     * 编辑
     * @param array $data
     * @param $loginInfo
     * @return mixed
     */
    public function edit(array $data, $loginInfo)
    {
        $data['update_user_id'] = $loginInfo['id'];;
        $data['update_user_name'] = $loginInfo['username'];
        return $this->exchangeCate->edit($data);
    }
    /**
     * 批量删除
     * @param string|null $ids
     * @param $loginInfo
     * @return mixed
     */
    public function delBatch($ids, $loginInfo)
    {
        $data['update_user_id'] = $loginInfo['id'];;
        $data['update_user_name'] = $loginInfo['username'];
        $data['is_delete'] = 1;
        return  $this->exchangeCate->delBatch($data,$ids);
    }


    /**
     * 详情
     * @param $id
     * @return mixed
     */
    public function getDetailById($id)
    {
        return $this->exchangeCate->getDetailById($id);
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
        return $this->exchangeCate->updateStatus($data);
    }

    /**
     * 商品分类列表
     * @return mixed
     */
    public function getCateList()
    {
        return $this->exchangeCate->getCateLists();
    }
}
