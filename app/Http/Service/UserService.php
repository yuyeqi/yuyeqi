<?php


namespace App\Http\Service;


use App\Models\Config;
use App\Models\ExchangeRecord;
use App\Models\Promoter;
use App\Models\ScoreDeal;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\UserStatistic;
use App\Models\WalletDeal;
use App\Models\Withdraw;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
    //用户地址
    private $userAddress;

    /**
     * SlideshowService constructor.
     */
    public function __construct()
    {
        $this->user = isset($this->user) ?: new User();
        $this->userStatistic = isset($this->userStatistic) ?: new UserStatistic();
        $this->userAddress = isset($this->userAddress) ?: new UserAddress();
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

    /**
     * 积分对话现金
     * @param $score
     * @param $userInfo
     * @return bool
     */
    public function exchangeCash($score, $remark, $userInfo)
    {
        //1.查询用的账户信息,校验积分
        $accountInfo = UserStatistic::getAccountDetail($userInfo["id"]);
        Log::info('[积分兑换,用户信息为：' . json_encode($accountInfo));
        if (!$accountInfo || $accountInfo['status'] != 10) {
            Log::error("积分兑换现金-用户不存在");
            $this->setErrorMsg("用户不存在！");
            return false;
        }
        //检测积分余额是否大于兑现积分
        $diffScore = bcsub($accountInfo['score'], $score, 2);
        if ($diffScore < 0) {
            Log::error("积分兑换-积分不足");
            $this->setErrorMsg("积分不足！");
            return false;
        }
        //2.获取兑换比例
        $exchageRate = Config::getConfigByNo("scoreToCash");
        if (empty($exchageRate) || $exchageRate <= 0) {
            Log::error("积分兑换-缺少兑换比例，请联系管理员");
            $this->setErrorMsg("缺少兑换比例，请联系管理人员!");
            return false;
        }
        //3.计算用户金额账户
        $cash = bcdiv($score, $exchageRate, 2);    //提现金额
        $newCash = bcadd($cash, $accountInfo["amount"], 2); //剩余金额
        //积分交易记录数据
        $dealNo = $this->getOrderNo("cz");
        $scoreLog = [
            'deal_no' => $dealNo,
            'user_id' => $userInfo['id'],
            'user_name' => $userInfo['user_name'],
            'deal_score' => $score,
            'surplus_score' => $diffScore,
            'deal_type' => 4,
            'remark' => $remark,
            'create_time' => time()
        ];
        //现金记录交易数据
        $cashLog = [
            'deal_no' => $dealNo,
            'user_id' => $userInfo['id'],
            'user_name' => $userInfo['user_name'],
            'amount' => $cash,
            'surplus_amount' => $newCash,
            'deal_type' => 2,
            'remark' => $remark,
            'create_time' => time()
        ];
        //更新账户数据
        $withdrawScore = $accountInfo['withdraw_score']; //以用积分
        $cushCore = $accountInfo['cush_score']; //已兑现积分
        $accountData = [
            'amount' => $newCash,   //账户余额
            'score' => $diffScore,  //账户剩余积分
            'withdraw_score' => bcadd($withdrawScore, $score, 2),
            'cush_score' => bcadd($cushCore, $score, 2)
        ];
        //开启事务
        DB::beginTransaction();
        try {
            //1.更新用户账户
            UserStatistic::where(['id' => $userInfo['id']])->update($accountData);
            //2.记录积分交易
            ScoreDeal::create($scoreLog);
            //3.记录现金交易
            WalletDeal::create($cashLog);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            Log::error("[积分兑换]-积分兑换失败" . json_encode($e->getMessage()));
            $this->setErrorCode(0);
            $this->setErrorMsg($e);
            DB::rollBack();
            return false;
        }
    }

    /**
     * 用户提现
     * @param string|null $cush
     * @param string|null $remark
     * @param array $userInfo
     * @return bool
     */
    public function withdraw($cush, $remark, $userInfo)
    {
        //1.查询用的账户信息,校验余额
        $accountInfo = UserStatistic::getAccountDetail($userInfo["id"]);
        if (!$accountInfo || $accountInfo['status'] != 10) {
            Log::error("[用户提现]-用户信息不存在");
            $this->setErrorMsg("用户不存在！");
            return false;
        }
        //检测提现的最小金额
        $minWithdraw = Config::getConfigByNo("minWithdraw");
        if ($cush < $minWithdraw) {
            Log::error("[用户提现]-提现金额小于" . $minWithdraw);
            $this->setErrorMsg("提现金额不能小于" . $minWithdraw . '元');
            return false;
        }
        //3检测余额是否大于提现金额
        $diffCush = bcsub($accountInfo['amount'], $cush, 2);
        if ($diffCush < 0) {
            Log::error('[用户提现]-用户余额不足，提现金额：' . $cush . '，现金余额:' . $accountInfo['amount']);
            $this->setErrorMsg("余额不足！");
            return false;
        }
        //4.提现记录
        $dealNo = $this->getOrderNo("tx");
        $walletLog = [
            'deal_no' => $dealNo,
            'user_id' => $userInfo['id'],
            'user_name' => $userInfo['user_name'],
            'amount' => $cush,
            'surplus_amount' => $diffCush,
            'remark' => $remark,
            'create_time' => time()
        ];
        //5.更新账户数据
        $accountData = [
            'amount' => $diffCush,   //账户余额
            'frozen_amount' => bcadd($accountInfo['withdraw_amount'], $cush, 2)   //冻结金额
        ];
        //开启事务
        DB::beginTransaction();
        try {
            //1.更新用户账户
            UserStatistic::where(['id' => $userInfo['id']])->update($accountData);
            //2.提现记录
            Withdraw::create($walletLog);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            Log::error('[用户提现]-' . json_encode($e->getMessage()));
            DB::rollBack();
            $this->setErrorCode(0);
            $this->setErrorMsg($e);
            return false;
        }
    }

    /**
     * 钱包明细
     * @param $userInfo
     * @param $field
     * @param $page
     * @param $limit
     * @return array
     */
    public function getWalletList($userInfo, $dealType, $page, $limit)
    {
        $field = ['id', 'amount', 'deal_type', 'create_time'];
        $lists = WalletDeal::getWalletList($userInfo, $field, $dealType, $page, $limit);
        return $this->getPageData($lists);
    }

    /**
     * 推广用户
     * @param array $userInfo
     * @param string|null $status
     * @param string|null $page
     * @param string|null $limit
     * @return array
     */
    public function getPromoterLists($userInfo, $page, $limit)
    {
        $field = ['id', 'promoter_user_id', 'promoter_user_name', 'promoter_amount', 'share_type', 'create_time'];
        $lists = Promoter::getPromoterLists($userInfo, $field, $page, $limit);
        return $this->getPageData($lists);
    }

    /**
     * 积分记录列表
     * @param $userInfo
     * @param $dealType
     * @param $page
     * @param $limit
     * @return array
     */
    public function getScoreList($userInfo, $dealType, $page, $limit)
    {
        $field = ['id', 'deal_score', 'deal_type', 'create_time'];
        $lists = ScoreDeal::getScoreList($userInfo, $field, $dealType, $page, $limit);
        return $this->getPageData($lists);
    }

    /**
     * 商品兑换记录
     * @param $userInfo
     * @param $page
     * @param $limit
     * @return array
     */
    public function getExRecordLists($userInfo, $page, $limit)
    {
        $field = ['id', 'deal_score', 'goods_name', 'deal_status', 'create_time'];
        $lists = ExchangeRecord::getExRecordLists($userInfo, $field, $page, $limit);
        return $this->getPageData($lists);
    }


    /**
     * 用户地址列表
     * @param $userInfo
     * @param $page
     * @param $limit
     * @return mixed
     */
    public function getApiUserAddressLists($userInfo, $page, $limit)
    {
        return $this->userAddress->getApiUserAddressLists($userInfo, $page, $limit);
    }

    /**
     * 添加用户收货地址
     * @param $userInfo
     * @param $data
     * @return bool
     */
    public function addUserAddress($userInfo, $data)
    {
        Log::info('【添加用户地址开始】--用户信息：userInfo' . json_encode($userInfo) . '，添加数据：data:' . json_encode($data));
        $data['user_id'] = $userInfo['id'];
        $data['user_name'] = $userInfo['user_name'];
        //开启事务
        DB::beginTransaction();
        try {
            //1.查询用户是否有默认地址,没有设置当前的地址为默认地址
            $defaultAddress = UserAddress::getDefaultUserAddress($userInfo['id']);
            Log::info('【添加用户地址】-----defaultAddress：' . json_encode($defaultAddress));
            $defaultStatus = 0;
            $defaultAddress->isEmpty() && $defaultStatus = 1;
            $data['default_status'] = $defaultStatus;
            //2.添加收货地址
            $addresss = UserAddress::create($data);
            //3.是用户的第一个地址则改用户的默认地址
            if ($defaultStatus == 1 && $addresss) {
                $this->user->updateUserAddress($userInfo['id'], $addresss->id);
            }
            Log::info('【添加用户地址】----添加成功,添加的记录数据：address:' . json_encode($addresss));
            DB::commit();
            return true;
        } catch (\Exception $e) {
            dd($e->getMessage());
            Log::error('【添加用户地址失败】----错误信息：e:' . json_encode($e->getMessage()));
            $this->setErrorMsg("系统异常，请稍后再试！");
            DB::rollBack();
            return false;
        }
    }

    /**
     * 修改地址
     * @param $userInfo
     * @param $data
     * @return bool
     */
    public function editUserAddress($userInfo, $data)
    {
        Log::info('【修改用户地址开始】--用户信息：userInfo' . json_encode($userInfo) . '，修改数据：data:' . json_encode($data));
        $data['user_id'] = $userInfo['id'];
        $data['user_name'] = $userInfo['user_name'];
        //开启事务
        DB::beginTransaction();
        try {
            //1.修改收货地址
            $addresss = $this->userAddress->updateUserAddress($data);
            Log::info('【修改用户地址】----修改成功,添加的记录数据：address:' . json_encode($addresss));
            DB::commit();
            return true;
        } catch (\Exception $e) {
            Log::error('【修改用户地址失败】----错误信息：e:' . json_encode($e->getMessage()));
            $this->setErrorMsg("系统异常，请稍后再试！");
            DB::rollBack();
            return false;
        }
    }

    /**
     * 删除用户地址
     * @param $id
     * @return mixed
     */
    public function delUserAddress($id)
    {
        return $this->userAddress->delUserAddress($id);
    }

    /**
     * 设置用户默认地址
     * @param $userInfo
     * @param $id
     * @return bool
     */
    public function setDefaultUserAddress($userInfo,$id){
        Log::info('【设置用户默认地址开始】----用户信息：userInfo = '.json_encode($userInfo).',id='.$id);
        //开启事务
        DB::beginTransaction();

        try {
            //1.恢复全部地址
            $this->userAddress->rebackDefaultUserAddress($userInfo['id']);
            //2.设置默认地址
            $this->userAddress->setDefaultUserAddress($id);
            //3.修改用户默认地址
            $this->user->updateUserAddress($userInfo['id'],$id);
            Log::info('【设置用户默认地址】----设置成功');
            DB::commit();
            return true;
        } catch (\Exception $e) {
            Log::error('【设置用户默认地址】----错误信息：e:' . json_encode($e->getMessage()));
            $this->setErrorMsg("系统异常，请稍后再试！");
            DB::rollBack();
            return false;
        }
    }
}
