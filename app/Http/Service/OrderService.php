<?php


namespace App\Http\Service;


use App\Models\Order;
use App\Models\User;
use App\Models\UserStatistic;
use Illuminate\Support\Facades\DB;

/**
 * 用户服务层
 * Class SlideshowService
 * @package App\Http\Service
 */
class OrderService extends BaseSerivce
{
    //用户模型
    private $order;

    /**
     * OrderService constructor.
     */
    public function __construct()
    {
        $this->order = isset($this->order) ?: new Order();
    }

    /**
     * 订单列表
     * * @param $id
     * @param $keywords
     * @param $userType
     * @param $status
     * @param $auditStatus
     * @param $limit
     * @return mixed
     */
    public function getLists($id,$keywords, $pay_status,$startTime,$endTime, $limit){
        return $this->order->getLists($id,$keywords, $pay_status,$startTime,$endTime, $limit);
    }

    /**
     * 修改用户信息
     * @param $data
     * @param $loginInfo
     * @return bool
     */
    public function edit($data,$loginInfo){
        //用户账户数据
        $userStatistic = [
            'user_name'=>$data['user_name'],
            'id'=>$data['id'],
            'phone' => $data['phone'],
            'update_user_id' => $loginInfo['update_user_id'],
            'update_user_name' => $loginInfo['update_user_name'],
        ];
        //用户数据
        $data['update_user_id'] = $loginInfo['id'];;
        $data['update_user_name'] = $loginInfo['username'];
        //开启事务
        DB::beginTransaction();
        try {
            //更新用户信息
            $this->user->updateUserInfoById($data);
            //更新用户账户信息
            $this->userStatistic->updateAccout($userStatistic);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->setErrorCode(0);
            $this->setErrorMsg($e->getMessage());
            return false;
        }

    }

    /**
     * 审核用户
     * @param $data
     * @return mixed
     */
    public function userAudit($data,$loginInfo){
        $data['update_user_id'] = $loginInfo['update_user_id'];
        $data['update_user_name'] = $loginInfo['update_user_name'];
        return $this->user->auditUser($data);
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
        return  $this->user->delBatch($data,$ids);
    }
    /**
     * 后端详情
     * @param $id
     * @return mixed
     */
    public function getUserDetail($id)
    {
        return $this->user->getUserDetail($id);
    }

    /**
     * 更新用户状态
     * @param $data
     * @param $loginInfo
     * @return mixed
     */
    public function updateStatus($data,$loginInfo){
        $data['update_user_id'] = $loginInfo['id'];;
        $data['update_user_name'] = $loginInfo['username'];
        return $this->user->updateStatus($data);
    }
    /*-----------------------------小程序端-------------------------------------*/
    /**
     * 小程序用户信息
     * @return mixed
     */
    public function getUserInfo($id){
        return $this->user->getUserInfo($id);
    }


}
