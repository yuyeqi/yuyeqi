<?php


namespace App\Http\Service;

use App\Models\Exchange;
use App\Models\ExchangeRecord;
use App\Models\Goods;
use App\Models\Picture;
use App\Models\ScoreDeal;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\UserStatistic;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * 兑换商品service
 * Class GoodsService
 * @package App\Http\Service
 */
class ExchangeService extends BaseSerivce
{
    //商品模型
    protected $exchange = null;

    //图片模型
    protected $picture = null;

    //用户模型
    private $userStatistic = null;

    //兑换记录
    private $record;
    /**
     * GoodsService constructor.
     */
    public function __construct()
    {
        $this->exchange = isset($this->exchange) ?: new Exchange();
        $this->picture = isset($this->picture) ?: new Picture();
        $this->userStatistic = isset($this->userStatistic) ?: new UserStatistic();
        $this->record = isset($this->record) ?: new ExchangeRecord();
    }

    /**
     * 兑换商品列表
     * @param $keywords
     * @param $limit
     * @return mixed
     */
    public function getLists($keywords,$limit){
        return $this->exchange->getGoodsLists($keywords,$limit);
    }

    /**
     * 商品详情
     * @param $id
     * @return mixed
     */
    public function getGoodsDetailById($id){
        return $this->exchange->getGoodsDetailById($id);
    }

    /**
     * 添加商品
     * @param $data
     * @param $loginInfo
     * @return bool
     */
    public function addGoods($data,$loginInfo){
        //商品数据
        $data['create_user_id'] = $loginInfo['id'];
        $data['create_user_name'] = $loginInfo['username'];
        $data['update_user_id'] = $loginInfo['id'];
        $data['update_user_name'] = $loginInfo['username'];
        //保存商品数据
        //开启事务
        DB::beginTransaction();
        try {
            $res = $this->exchange->addGoods($data);//添加商品
            $img = [];
            if (isset($data['mulPic']) && is_array($data['mulPic']) && !empty($data['mulPic'])) {
                foreach ($data['mulPic'] as $key => $item) {
                    $img[$key]['pic_id'] = $res->id;
                    $img[$key]['pic_type'] = 3;
                    $img[$key]['pic_url'] = $item;
                }
                $this->picture->addPicture($img);
            }
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->setErrorCode(0);
            $this->setErrorMsg($e);
            return false;
        }
    }

    /**
     * 修改商品
     * @param $data
     * @param $loginInfo
     * @return bool
     */
    public function updateGoods($data,$loginInfo){
        //商品数据
        $data['create_user_id'] = $loginInfo['id'];
        $data['create_user_name'] = $loginInfo['username'];
        $data['update_user_id'] = $loginInfo['id'];
        $data['update_user_name'] = $loginInfo['username'];
        if ($data['status'] == 20){
            $data['deliver_time'] = date('Y-m-d h:m:s',time());
        }
        $mulPic = $data['mulPic'];
        unset($data['mulPic']);
        //保存商品数据
        //开启事务
        DB::beginTransaction();
        try {
            //添加新的轮播
            $res = $this->exchange->updateGoods($data);
            //删除原来的图片
            $this->picture->deletePic($data["id"]);
            $img = [];
            if (isset($mulPic) && is_array($mulPic) && !empty($mulPic)) {
                foreach ($mulPic as $key => $item) {
                    $img[$key]['pic_id'] = $data['id'];
                    $img[$key]['pic_type'] = 3;
                    $img[$key]['pic_url'] = $item;
                }
                $this->picture->addPicture($img);
            }
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->setErrorCode(0);
            $this->setErrorMsg($e);
            return false;
        }
    }
    /**
     * 删除商品
     * @param $ids
     * @param $loginInfo
     * @return bool|null
     * @throws \Exception
     */
    public function delBatch($ids,$loginInfo){
        $data['update_user_id'] = $loginInfo['id'];;
        $data['update_user_name'] = $loginInfo['username'];
        $data['is_delete'] = 1;
        return $this->goods->delBatch($ids,$data);
    }

    /**
     * 兑换记录
     * @param $keywords
     * @param $page
     * @param $limit
     * @return mixed
     */
    public function getRecordList($keywords,$page,$limit){
        return $this->record->getRecordList($keywords,$page,$limit);
    }

    /**
     * 修改状态
     * @param array $data
     * @param $loginInfo
     * @return mixed
     */
    public function audit(array $data, $loginInfo)
    {
        $data['update_user_id'] = $loginInfo['id'];;
        $data['update_user_name'] = $loginInfo['username'];
        return $this->record->updateStatus($data);
    }
    /**
     * 删除兑换记录
     * @param $ids
     * @param $loginInfo
     * @return bool|null
     * @throws \Exception
     */
    public function delBatchRecord($ids,$loginInfo){
        $data['update_user_id'] = $loginInfo['id'];;
        $data['update_user_name'] = $loginInfo['username'];
        $data['is_delete'] = 1;
        return $this->record->delBatchRecord($ids,$data);
    }

/*----------------------------------------小程序------------------------------------------------------*/
    /**
     * 小程序首页新品推荐
     * @return mixed
     */
    public function getNesGoods()
    {
        return $this->goods->getNewsGoods();
    }

    /**
     * 兑换商品分类
     * @return mixed
     */
    public function getApiGoodsLists($cateType,$page,$limit){
        $pageData = $this->exchange->getApiGoodsLists($cateType,$page,$limit);
        return $this->getPageData($pageData);
    }

    /**
     * 商品兑换
     * @param $userInfo
     * @param $goods_id
     * @return bool
     */
    public function createOrder($userInfo,$goods_id){
        Log::info('【商品兑换】---用户信息：userInfo='.json_encode($userInfo).',商品id='.$goods_id);
        //1.用户账户信息,检测用户账户积分
        $userAccount = UserStatistic::getAccountDetail($userInfo['id']);
        if (!$userAccount) {
            Log::error('【商品兑换】---用户不存在');
            $this->setErrorMsg('用户不存在，请联系管理员');
            return false;
        }
        //2.检测用户是否有收货地址
        $user = User::getUserDetail($userInfo['id']);
        if ($user->delivery_id <= 0){
            Log::error('【商品兑换】----用户没有默认收货地址，userInfo='.json_encode($user));
            $this->setErrorMsg('请设置默认收货地址');
            return  false;
        }
        //3.检测收货地址是否存在
        $userDefaultAddress = UserAddress::getDefaultUserAddress($user->delivery_id);
        if (!$userDefaultAddress){
            Log::error('【商品兑换】----默认收货地址不存在');
            $this->setErrorMsg('默认地址不存在');
            return  false;
        }
        //4.获取商品详情
        $goodsDetail = Exchange::getApiGoodsDetail($goods_id);
        if (!$goodsDetail){
            Log::error('【商品兑换】---商品不存在：id='.$goods_id);
            $this->setErrorMsg('商品不存在或已下架');
            return false;
        }
        //5检测商品库存
        if ($goodsDetail->stock_num <= 0){
            Log::error('【商品兑换】---商品库存不足：id='.$goods_id);
            $this->setErrorMsg('商品库存不足');
            return false;
        }
        //6.检测用户积分是足够
        $userScore = $userAccount->score;   //用户积分
        $goodsScore = $goodsDetail->sales_score;    //兑换商品积分需要的积分
        $diffScore = bcsub($userScore,$goodsScore);
        if ($diffScore < 0){
            Log::error('【商品兑换】----用户积分不足，用户积分：userScore='.$userScore.',商品积分：goodsScore='.$goodsScore);
            $this->setErrorMsg('积分不足');
            return  false;
        }
        Log::error('【积分兑换】----用户积分：userScore='.$userScore.',商品积分:goodsScore='.$goodsScore.',用户剩余积分；socre='.$diffScore);
        //7.生成兑换订单数据
        $orderNo = $this->getOrderNo('DH');
        $exchangeData = [
            'deal_no' => $orderNo,
            'user_id' => $userInfo['id'],
            'address_id' => $user['delivery_id'],
            'user_name' => $userInfo['user_name'],
            'goods_id' => $goodsDetail['id'],
            'goods_name' => $goodsDetail['goods_name'],
            'deal_score' => $goodsDetail['sales_score'],
            'surplus_score' => $diffScore
        ];
        Log::info('【商品兑换】----兑换订单数据：exchangeData='.json_encode($exchangeData));
        //开启事务
        DB::beginTransaction();
        try {
            //8.创建兑换订单
            ExchangeRecord::create($exchangeData);
            //9.修改兑换的兑换数量
            $this->exchange->updateExchangeNum($goods_id);
            //7.修改用户的兑换的数量和积分
            $accountData = [
                'user_id' => $userInfo['id'],
                'score' => $diffScore,
                'withdraw_score' => bcadd($userAccount['withdraw_score'],$goodsScore),
                'present_score' => bcadd($userAccount['present_score'],$goodsScore)
            ];
            Log::info('【商品兑换】----更新的账户信息:account='.json_encode($accountData));
            $this->userStatistic->updateExchangeNum($accountData);
            //10.生成积分兑换记录
            $scoreLog = [
                'deal_no' => $orderNo,
                'user_id' => $userInfo['id'],
                'user_name' => $userInfo['user_name'],
                'deal_score' => $goodsScore,
                'surplus_score' => $diffScore,
                'deal_type' => 5,
                'remark' => '商品兑换'
            ];
            ScoreDeal::create($scoreLog);
            Log::info('【商品兑换】----兑换成功');
            //11.减少库存
            $this->exchange->updateExchangeStock($goods_id);
            //提交事务
            DB::commit();
            return  true;
        } catch (\Exception $e) {
            Log::error('【商品兑换】----系统异常：e='.json_encode($e->getMessage()));
            $this->setErrorMsg('系统异常，请稍后再试！');
            return  false;
        }

    }
}
