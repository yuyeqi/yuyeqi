<?php


namespace App\Http\Service;


use App\Models\Person;
use Illuminate\Support\Facades\Log;

/**
 * 私人定制服务层
 * Class PersonService
 * @package App\Http\Service
 */
class PersonService extends BaseSerivce
{
    private $person;

    /**
     * PersonService constructor.
     */
    public function __construct()
    {
        $this->person = isset($this->person) ?: new Person();
    }


    /**
     * 提交私人定制信息
     * @param array $data
     * @return mixed
     */
    public function addPerson($userInfo,$data)
    {
        //每月只能提交一次
        if ($this->person->getMonthPerson($userInfo['id']) > 0){
            Log::error('【私人定制】');
            $this->setErrorMsg('你本月已经提交过定制计划');
            return false;
        }
        $data['user_id'] = $userInfo['id'];
        return $this->person->addPerson($data);
    }

    /**
     * 私人定制列表
     * @param $keywords
     * @param $userinfo
     * @param $cateId
     * @param $startTime
     * @param $endTime
     * @param int $limit
     * @return mixed
     */
    public function getPersonLists($keywords, $cateId, $startTime, $endTime, int $limit)
    {
        return $this->person->getPersonLists( $keywords, $cateId,$startTime,$endTime,$limit);
    }

    /**
     * 定制详情
     * @param $id
     * @return mixed
     */
    public function getAdminPersonById($id)
    {
        return $this->person->getAdminPersonById($id);
    }

    /**
     * 编辑
     * @param array $data
     * @param $loginInfo
     * @return mixed
     */
    public function editPerson(array $data, $loginInfo)
    {
        $data['update_user_id'] = $loginInfo['id'];;
        $data['update_user_name'] = $loginInfo['username'];
        return $this->person->editPerson($data);
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
        return  $this->person->delBatch($data,$ids);
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
        return $this->person->updateStatus($data);
    }


}
