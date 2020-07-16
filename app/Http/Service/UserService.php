<?php


namespace App\Http\Service;


use App\Models\Config;
use App\Models\User;
use App\Models\UserStatistic;
use Illuminate\Support\Facades\DB;

/**
 * 用户服务层
 * Class SlideshowService
 * @package App\Http\Service
 */
class UserService extends BaseSerivce
{
    //用户模型
    private $user;
    //用户账户
    private $userStatistic;

    /**
     * SlideshowService constructor.
     */
    public function __construct()
    {
        $this->user = isset($this->user) ?: new User();
        $this->userStatistic = isset($this->userStatistic) ?: new UserStatistic();
    }

    /**
     * 用户列表
     * @param $keywords
     * @param $userType
     * @param $status
     * @param $auditStatus
     * @param $limit
     * @return mixed
     */
    public function getLists($keywords, $userType, $status, $auditStatus, $limit)
    {
        return $this->user->getLists($keywords, $userType, $status, $auditStatus, $limit);
    }

    /**
     * 修改用户信息
     * @param $data
     * @param $loginInfo
     * @return bool
     */
    public function edit($data, $loginInfo)
    {
        //用户账户数据
        $userStatistic = [
            'user_name' => $data['user_name'],
            'id' => $data['id'],
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
    public function userAudit($data, $loginInfo)
    {
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
    public function delBatch(array $ids, $loginInfo)
    {
        $data['update_user_id'] = $loginInfo['id'];;
        $data['update_user_name'] = $loginInfo['username'];
        $data['is_delete'] = 1;
        return $this->user->delBatch($data, $ids);
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
    public function updateStatus($data, $loginInfo)
    {
        $data['update_user_id'] = $loginInfo['id'];;
        $data['update_user_name'] = $loginInfo['username'];
        return $this->user->updateStatus($data);
    }
    /*-----------------------------小程序端-------------------------------------*/
    /**
     * 小程序用户信息
     * @return mixed
     */
    public function getUserInfo($id)
    {
        return $this->user->getUserInfo($id);
    }

    /**
     * 用户账户信息
     * @param $userInfo
     * @return mixed
     */
    public function getUserAccount($userInfo)
    {
        return $this->user->getUserAccount($userInfo["id"]);
    }

    public function exchangeCash($score, $userInfo)
    {
        //1.查询用的账户信息,校验积分
        $accountInfo = UserStatistic::getAccountDetail($userInfo["id"]);
        if (!$accountInfo || $accountInfo['stauts'] != 10) {
            $this->setErrorMsg("用户不存在！");
            return false;
        }
        //检测积分余额是否大于兑现积分
        $diffScore = bcsub($accountInfo['score'], $score);
        if ($diffScore < 0) {
            $this->setErrorMsg("积分不足！");
            return false;
        }
        //2.获取兑换比例
        $exchageRate = Config::getConfigByNo("scoreToCash");
        //3.计算兑换的现金
        $cash = bcdiv($score, $exchageRate);
        //开启事务
        DB::beginTransaction();
        try {
            DB::commit();
            return true;
        } catch (\Exception $e) {
        }
        //4.兑换现金，修改账户信息
        //5.生成兑换记录
    }


}
