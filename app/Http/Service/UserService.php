<?php


namespace App\Http\Service;


use App\Models\Config;
use App\Models\ExchangeRecord;
use App\Models\Promoter;
use App\Models\ScoreDeal;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\UserCate;
use App\Models\UserStatistic;
use App\Models\WalletDeal;
use App\Models\Withdraw;
use EasyWeChat\Factory;
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
    //推荐人
    private $promoter;
    //提现
    private $withdraw;
    //钱包交易
    private $walletDeal;
    //积分交易
    private $scoreDeal;
    //微信支付
    private $app;

    /**
     * SlideshowService constructor.
     */
    public function __construct()
    {
        $this->user = isset($this->user) ?: new User();
        $this->userStatistic = isset($this->userStatistic) ?: new UserStatistic();
        $this->userAddress = isset($this->userAddress) ?: new UserAddress();
        $this->promoter = isset($this->promoter) ?: new Promoter();
        $this->withdraw = isset($this->withdraw) ?: new Withdraw();
        $this->walletDeal = isset($this->walletDeal) ?: new WalletDeal();
        $this->scoreDeal = isset($this->scoreDeal) ?: new ScoreDeal();
        $config = config('wechat.payment.default');
        $this->app = isset($this->app) ?: Factory::payment($config);
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
            //$this->userStatistic->updateAccout($userStatistic);
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
        $data['update_user_id'] = $loginInfo['id'];
        $data['update_user_name'] = $loginInfo['username'];
        $data['audit_user_id'] = $loginInfo['id'];
        $data['audit_user_name'] = $loginInfo['username'];
        //1.获取用户信息
        $userInfo = User::getUserDetail($data['id']);
        if (!$userInfo) {
            $this->setErrorMsg('用户不存在');
            return false;
        }
        //判断用户是否审核过
        if ($userInfo->audit_status > 1) {
            $this->setErrorMsg('禁止重复审核');
            return false;
        }
        //2.根据用户分类配置
        $cateInfo = UserCate::getUserCateInfoByUserType($userInfo->user_type);
        if (!$cateInfo) {
            $this->setErrorMsg('缺少用户分类');
            return false;
        }
        //3.是审核通过时添加数据
        if ($data['audit_status'] == 2) {
            //开启事务
            DB::beginTransaction();
            //1.添加用户统计信息
            try {
                $userStatistic = [
                    'user_id' => $data['id'],
                    'user_name' => $userInfo['user_name'],
                    'nick_name' => $userInfo['nick_name'],
                    'phone' => $userInfo['phone'],
                    'amount' => $cateInfo['register_account']
                ];
                $this->userStatistic->updateAccout($userStatistic);
                //2.判断用户是否有推荐人
                $promoterInfo = User::getUserDetail($userInfo->parent_id);
                if ($promoterInfo) {
                    //1.获取推广人的账户信息,推广人必须是注册成功的
                    if ($promoterInfo->audit_status != 2 || $promoterInfo->share_type > 0) {
                        $this->setErrorMsg('账户异常,请联系管理员');
                        return false;
                    }
                    $promoterAccount = UserStatistic::getAccountDetail($userInfo->parent_id);
                    //2审核通过修改推荐人的推荐人数和账户余额
                    $parentData = [
                        'id' => $userInfo->parent_id,
                        'children_num' => bcadd($promoterAccount->children_num, 1),
                        //'amount' => bcadd($promoterAccount->amount,$cateInfo->tg_account,2)
                    ];
                    $this->userStatistic->updatePromoterCount($parentData);
                    //3.添加推荐关系表
                    $dealNo = $this->getOrderNo("gz");
                    $promoterData = [
                        'deal_no' => $dealNo,
                        'promoter_id' => $userInfo->parent_id,
                        'promoter_user' => $userInfo->parent_name,
                        'promoter_user_id' => $userInfo->id,
                        'promoter_user_name' => $userInfo->user_name,
                        'promoter_amount' => $cateInfo->tg_account,
                        'promoter_surplus_amount' => bcadd($promoterAccount->amount, $cateInfo['tg_account'], 2),
                        'promoter_type' => $userInfo->user_type,
                        'amount' => $cateInfo->register_account,
                        'share_type' => $userInfo->share_type
                    ];
                    Promoter::create($promoterData);
                    //赠送推广人金额
                    $tgDealNo = $this->getOrderNo('tg');
                    $stgCushLog = [
                        'deal_no' => $tgDealNo,
                        'user_id' => $promoterInfo->id,
                        'user_name' => $promoterInfo->user_name,
                        'amount' => $cateInfo->tg_account,
                        'surplus_amount' => bcadd($promoterAccount->amount, $cateInfo->tg_account, 2),
                        'deal_type' => 4,
                        'remark' => '推广赠送金额'
                    ];
                    WalletDeal::create($stgCushLog);
                }
                //3.修改审核状态
                $this->user->updateUserStatus($data);
                //4.注册赠送金额
                $rgCushLog = $this->getOrderNo('zc');
                //5.获取用户信息
                $userAccount = UserStatistic::getAccountDetail($data['id'], ['id', 'amount']);
                $rcgCushLog = [
                    'deal_no' => $rgCushLog,
                    'user_id' => $userInfo->id,
                    'user_name' => $userInfo->user_name,
                    'amount' => $cateInfo->register_account,
                    'surplus_amount' => bcadd($userAccount->amount, $cateInfo->register_account, 2),
                    'deal_type' => 3,
                    'remark' => '注册赠送金额'
                ];
                WalletDeal::create($rcgCushLog);
                DB::commit();
                return true;
            } catch (\Exception $e) {
                $this->setErrorMsg($e->getMessage());
                DB::rollBack();
                return false;
            }

        }
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
        Log::info('【积分兑换】-----用户信息为：' . json_encode($accountInfo));
        if (!$accountInfo || $accountInfo['status'] != 10) {
            Log::error("积分兑换现金-用户不存在");
            $this->setErrorMsg("用户不存在！");
            return false;
        }
        //检测积分余额是否大于兑现积分
        $diffScore = bcsub($accountInfo['score'], $score, 2);
        if ($diffScore < 0) {
            Log::error("【积分兑换】-------积分不足");
            $this->setErrorMsg("积分不足！");
            return false;
        }
        //2.获取兑换比例
        $config = Config::getConfigByNo("scoreToCash");
        if (!$config) {
            Log::info('【积分兑换】----缺少积分兑换配置项');
            $this->setErrorMsg("缺少系统配置，请联系管理人员");
            return false;
        }
        $exchageRate = $config->config_value ?? 0;
        if (empty($exchageRate) || $exchageRate <= 0) {
            Log::error("积分兑换-缺少兑换比例，请联系管理员");
            $this->setErrorMsg("缺少兑换比例，请联系管理人员!");
            return false;
        }
        //3.计算用户金额账户
        $cash = bcdiv($score, $exchageRate, 2);    //提现金额
        $newCash = bcadd($cash, $accountInfo["amount"], 2); //剩余金额
        //4积分交易记录数据
        $dealNo = $this->getOrderNo("cz");
        $scoreLog = [
            'deal_no' => $dealNo,
            'user_id' => $userInfo['id'],
            'user_name' => $userInfo['user_name'],
            'deal_score' => $score,
            'deal_amount' => $cash,
            'surplus_score' => $diffScore,
            'deal_type' => 4,
            'remark' => '积分兑换现金'
        ];
        //5.现金记录交易数据
        $cashLog = [
            'deal_no' => $dealNo,
            'user_id' => $userInfo['id'],
            'user_name' => $userInfo['user_name'],
            'amount' => $cash,
            'surplus_amount' => $newCash,
            'deal_type' => 2,
            'remark' => '积分兑换现金'
        ];
        //6.更新账户数据
        $withdrawScore = $accountInfo['withdraw_score']; //以用积分
        $cushCore = $accountInfo['cush_score']; //已兑现积分
        $accountData = [
            'user_id' => $userInfo['id'],
            'amount' => $newCash,   //账户余额
            'score' => $diffScore,  //账户剩余积分
            'withdraw_score' => bcadd($withdrawScore, $score, 2),
            'cush_score' => bcadd($cushCore, $score, 2)
        ];
        //开启事务
        DB::beginTransaction();
        try {
            //1.更新用户账户
            $this->userStatistic->updateAccout($accountData);
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
        $config = Config::getConfigByNo("withdrawCush");
        $minWithdraw = 0;
        if (!$config) {
            Log::info('【用户提现】----最小金额未配置');
            $this->setErrorMsg("缺少系统配置，请联系管理人员");
            return false;
        }
        $minWithdraw = $config->config_value ?? 0;
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
            'remark' => '用户提现'
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
            UserStatistic::where(['user_id' => $userInfo['id']])->update($accountData);
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
        $field = ['promoter.id', 'promoter_user_id', 'promoter_user_name', 'promoter_amount', 'promoter.share_type', 'promoter.create_time', 'u.avatar_url'];
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
        $field = ['id', 'deal_score','deal_amount', 'deal_type', 'create_time'];
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
        $field = ['id','deal_no', 'deal_score', 'goods_name', 'deal_status', 'create_time as date_format(create_time,"%Y-%m-%d")'];
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
        $pageData = $this->userAddress->getApiUserAddressLists($userInfo, $page, $limit);
        return $this->getPageData($pageData);
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
    public function setDefaultUserAddress($userInfo, $id)
    {
        Log::info('【设置用户默认地址开始】----用户信息：userInfo = ' . json_encode($userInfo) . ',id=' . $id);
        //开启事务
        DB::beginTransaction();

        try {
            //1.恢复全部地址
            $this->userAddress->rebackDefaultUserAddress($userInfo['id']);
            //2.设置默认地址
            $this->userAddress->setDefaultUserAddress($id);
            //3.修改用户默认地址
            $this->user->updateUserAddress($userInfo['id'], $id);
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


    /**
     * 用户注册
     * @param $userInfo
     * @param $data
     * @return bool
     */
    public function register($data)
    {
        Log::info('【用户注册】----注册开始，注册数据：data=' . json_encode($data));
        //1.检测用户是否微信登录授权
        $userInfo = User::getUserDetail($data['id']);
        if (!$userInfo) {
            Log::error('【用户注册】----注册失败，注册要先微信授权，成功后才能注册');
            $this->setErrorMsg("请先微信授权");
            return false;
        }
        $data['audit_status'] = 1;
        return $this->user->register($data);
    }

    /**
     * 提现记录
     * @param array $userInfo
     * @param string|null $status
     * @param string|null $page
     * @param string|null $limit
     */
    public function getCushLists($userInfo, $status, $page, $limit)
    {
        $field = ['id', 'amount', 'status', 'create_time'];
        $pageData = Withdraw::getCushLists($userInfo, $field, $status, $page, $limit);
        return $this->getPageData($pageData);
    }

    /**
     * 提现列表
     * @param $goodsId
     * @param $keywords
     * @param $page
     * @param $limit
     * @return mixed
     */
    public function getWithdrawList($goodsId, $keywords, $page, $limit)
    {
        return $this->withdraw->getWithdrawList($goodsId, $keywords, $page, $limit);
    }

    /**
     * 提现列表
     * @param $goodsId
     * @param $keywords
     * @param $dealType
     * @param $page
     * @param $limit
     * @return mixed
     */
    public function walletDealList($goodsId, $keywords, $dealType, $page, $limit)
    {
        return $this->walletDeal->walletDealList($goodsId, $keywords, $dealType, $page, $limit);
    }

    /**
     * 积分交易列表
     * @param $goodsId
     * @param $keywords
     * @param $dealType
     * @param $page
     * @param $limit
     * @return mixed
     */
    public function scoreDealList($goodsId, $keywords, $dealType, $page, $limit)
    {
        return $this->scoreDeal->scoreDealList($goodsId, $keywords, $dealType, $page, $limit);
    }

    /**
     * 推广列表
     * @param $goodsId
     * @param $keywords
     * @param $dealType
     * @param $page
     * @param $limit
     * @return mixed
     */
    public function promoterlList($goodsId, $keywords, $dealType, $page, $limit)
    {
        return $this->promoter->promoterlList($goodsId, $keywords, $dealType, $page, $limit);
    }

    /**
     * 删除提现记录
     * @param array $ids
     * @param $loginInfo
     * @return mixed
     */
    public function delBatchWithdraw(array $ids, $loginInfo)
    {
        $data['update_user_id'] = $loginInfo['id'];;
        $data['update_user_name'] = $loginInfo['username'];
        $data['is_delete'] = 1;
        return $this->withdraw->delBatch($data, $ids);
    }

    /**
     * 删除钱包记录
     * @param array $ids
     * @param $loginInfo
     * @return mixed
     */
    public function delBatchWallet(array $ids, $loginInfo)
    {
        $data['update_user_id'] = $loginInfo['id'];;
        $data['update_user_name'] = $loginInfo['username'];
        $data['is_delete'] = 1;
        return $this->walletDeal->delBatch($data, $ids);
    }

    /**
     * 删除积分
     * @param array $ids
     * @param $loginInfo
     * @return mixed
     */
    public function delBatchScore(array $ids, $loginInfo)
    {
        $data['update_user_id'] = $loginInfo['id'];;
        $data['update_user_name'] = $loginInfo['username'];
        $data['is_delete'] = 1;
        return $this->scoreDeal->delBatch($data, $ids);
    }

    /**
     * 删除推广用户
     * @param array $ids
     * @param $loginInfo
     * @return mixed
     */
    public function delBatchPromoter(array $ids, $loginInfo)
    {
        $data['is_delete'] = 1;
        return $this->promoter->delBatch($data, $ids);
    }

    /**
     * 提现审核操作
     * @param $data
     * @param $loginInfo
     * @return bool
     */
    public function cushAudit($data, $loginInfo)
    {
        $data['update_user_id'] = $loginInfo['id'];;
        $data['update_user_name'] = $loginInfo['username'];
        //1.获取提现记录信息
        $cushInfo = Withdraw::getWalletdrawInfo($data['id']);
        if (!$cushInfo) {
            $this->setErrorMsg('提现记录不存在');
            return false;
        }
        if ($cushInfo->status == 20) {
            $this->setErrorMsg('提现已通过，请勿重复操作');
            return false;
        }
        //开启事务
        DB::beginTransaction();
        //2.如果审核通过,需要企业给用户转账到零钱
        try {
            //3.修改提现记录状态
            $this->withdraw->updateStatus($data);
            //4.提现人用户信息
            $accountInfo = UserStatistic::getAccountDetail($cushInfo->user_id);
            //5.提现用户的账户信息
            $userInfo = User::getUserDetail($cushInfo->user_id);
            if (!$accountInfo && !$userInfo) {
                $this->setErrorMsg('用户不存在,请联系管理员');
                return false;
            }
            if ($data['status'] == 20) {
                //6.修改用户账户信息
                $accountData = [
                    'user_id' => $data['id'],
                    'frozen_amount' => bcsub($accountInfo->frozen_amount, $userInfo->amount, 2),  //解冻账户金额
                    'withdraw_amount' => bcadd($accountInfo->withdraw_amount, $userInfo->amount, 2),  //增加提现金额
                ];
                $this->userStatistic->updateAccout($accountData);
                //7.生成提现记录
                $cushLog = [
                    'deal_no' => $cushInfo->deal_no,
                    'user_id' => $userInfo['id'],
                    'user_name' => $userInfo['user_name'],
                    'amount' => $cushInfo->amount,
                    'surplus_amount' => $cushInfo->surplus_amount,
                    'remark' => '用户提现'
                ];
                WalletDeal::create($cushLog);
                //8.企业转账到用户
                $payData = [
                    'partner_trade_no' => $cushInfo->deal_no, // 商户订单号，需保持唯一性(只能是字母或者数字，不能包含有符号)
                    'openid' => $userInfo->openid,
                    'check_name' => 'NO_CHECK', // NO_CHECK：不校验真实姓名, FORCE_CHECK：强校验真实姓名
                    're_user_name' => $cushInfo->user_name, // 如果 check_name 设置为FORCE_CHECK，则必填用户真实姓名
                    //'amount' => bcmul($cushInfo->amount, 100, 2), // 企业付款金额，单位为分
                    'amount' => 1, // 企业付款金额，单位为分
                    'desc' => '用户' . $cushInfo->user_name . '的账户提现', // 企业付款操作说明信息。必填
                ];
                $this->app->transfer->toBalance($payData);
            } elseif ($data['status'] == 30) {
                //6.拒绝申请,修改用户账户信息,返回余额
                $accountData = [
                    'frozen_amount' => bcsub($accountInfo->frozen_amount, $userInfo->amount, 2),  //解冻账户金额
                    'amount' => bcadd($accountInfo->amount, $userInfo->amount, 2),  //增加提现金额
                ];
                $this->userStatistic->updateAccout($accountData);
            }
            DB::commit();
            return true;
        } catch (\Exception $e) {
            dd($e->getMessage());
            $this->setErrorMsg($e->getMessage());
            DB::rollBack();
            return false;
        }
    }

    /**
     * \修改用户信息
     * @param $data
     * @return bool|mixed
     */
    public function updateUserData($data)
    {
        return $this->userStatistic->updateAccout($data);
    }

}
