<?php


namespace App\Http\Service;


use App\Models\UserStatistic;

/**
 * 用户账户服务层
 * Class SlideshowService
 * @package App\Http\Service
 */
class UserStatisticService extends BaseSerivce
{
    private $userStatistic;

    /**
     * SlideshowService constructor.
     */
    public function __construct()
    {
        $this->userStatistic = isset($this->userStatistic) ?: new UserStatistic();
    }

    /**
     * 用户账号列表
     * @param $sort
     * @param $limit
     * @return mixed
     */
    public function getLists($id,$keywords,$sort,$limit){
        return $this->userStatistic->getLists($id,$keywords,$sort,$limit);
    }

    /**
     * 更新账户信息
     * @param $data
     * @return mixed
     */
    public function updateAccount($data){
        return $this->userStatistic->updateAccout($data);
    }

    /**
     * 用户账户详情
     * @param $id
     * @return mixed
     */
    public function getDetail($id){
        return $this->userStatistic->getDetail($id);
    }
    /*-----------------------------小程序端-------------------------------------*/


}
