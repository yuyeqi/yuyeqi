<?php


namespace App\Http\Service;


use App\Models\Cases;

/**
 * 案例服务层
 * Class CasesService
 * @package App\Http\Service
 */
class PermissionService extends BaseSerivce
{
    private $cases;

    /**
     * CasesService constructor.
     */
    public function __construct()
    {
        $this->cases = isset($this->cases) ?: new Cases();
    }

    /**
     * 案例列表
     * @param string $keyword
     * @param int $limit
     * @return mixed
     */
    public function getCasesAdminLists(string $keyword, int $limit)
    {
        return $this->cases->getCasesAdminLists($keyword,$limit);

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

}
