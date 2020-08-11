<?php


namespace App\Http\Service;

use App\Models\Book;
use App\Models\ScoreDeal;
use App\Models\UserCate;
use App\Models\UserStatistic;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * 报备服务层
 * Class SlideshowService
 * @package App\Http\Service
 */
class BookService extends BaseSerivce
{
    private $book;  //客户预约模型
    private $userStatistic;    //用户统计模型

    /**
     * SlideshowService constructor.
     */
    public function __construct()
    {
        $this->book = isset($this->book) ?: new Book();
        $this->userStatistic = isset($this->userStatistic) ?: new UserStatistic();
    }

    /**
     * 报备列表
     * @return int
     */
    public function getBookList(){
        return $this->book->getBookList();
    }

    /**
     * 预约列表
     * @param array $data
     * @param int $limit
     * @return mixed
     */
    public function getBookAdminLists(array $data, int $limit)
    {
        return $this->book->getBookAdminLists($data,$limit);
    }

    /**
     * 修改状态
     * @param $data
     * @param $loginInfo
     * @return bool
     */
    public function updateStatus($data,$loginInfo){
        $data['update_user_id'] = $loginInfo['id'];;
        $data['update_user_name'] = $loginInfo['username'];
        //1.预约信息
        $bookInfo = Book::getApiBookDetail($data['id']);
        if (!$bookInfo){
            $this->setErrorMsg('预约信息不存在');
            return false;
        }
        //2.获取用户账户信息
        $accountInfo = UserStatistic::getAccountDetail($bookInfo->user_id);
        if (!$accountInfo){
            $this->setErrorMsg('用户信息不存在');
            return  false;
        }
        //状态验证
        if ($bookInfo->status >= $data['status']){
            $this->setErrorMsg('不可以重复操作');
            return  false;
        }
        //开启事务
        DB::beginTransaction();
        try {
            //3.修改状态
            $this->book->updateStatus($data);//4.预约到店赠送积分
            if ($data['status'] == 20) {
                //生成积分记录
                $dealNo = $this->getOrderNo("yy");
                $scoreLog = [
                    'deal_no' => $dealNo,
                    'user_id' => $bookInfo->user_id,
                    'user_name' => $bookInfo->user_name,
                    'deal_score' => $bookInfo->store_score,
                    'surplus_score' => bcadd($accountInfo->score, $bookInfo->store_score, 2),
                    'deal_type' => 2,
                    'remark' => '预约到店赠送积分'
                ];
                ScoreDeal::create($scoreLog);
            } else if ($data['status'] == 40) {
                //生成积分记录
                $dealNo = $this->getOrderNo("fc");
                $scoreLog = [
                    'deal_no' => $dealNo,
                    'user_id' => $bookInfo['user_id'],
                    'user_name' => $bookInfo['user_name'],
                    'deal_score' => $bookInfo->finished_score,
                    'surplus_score' => bcadd($accountInfo->score, $bookInfo->finished_score, 2),
                    'deal_type' => 3,
                    'remark' => '完成预定赠送积分'
                ];
                ScoreDeal::create($scoreLog);
            }
            DB::commit();
            return  true;
        } catch (\Exception $e) {
            $this->setErrorMsg('系统异常,请稍后再试');
            DB::rollBack();
            return  false;
        }


    }

    /**
     * 批量删除
     * @param array $ids
     * @param $loginInfo
     * @return mixed
     */
    public function delBatch(array $ids, $loginInfo)
    {
        $data['update_user_id'] = $loginInfo['id'];;
        $data['update_user_name'] = $loginInfo['username'];
        $data['is_delete'] = 1;
        return $this->book->delBatch($data, $ids);
    }
    /*-------------------------小程序------------------------------*/
    /**
     * 小程序预约列表
     * @param $userInfo
     * @param $page
     * @param $limit
     * @return mixed
     */
    public function getApiBookLists($userInfo,$page,$limit){
        $lists = $this->book->getApiBookLists($userInfo,$page,$limit);
        return  $this->getPageData($lists);
    }

    /**
     * 客户预约
     * @param $data
     * @param $userInfo
     * @return bool
     */
    public function addBook($data,$userInfo){
        Log::info('------------用户预约开始-------------用户id：'.$userInfo['id'].'，用户姓名：'.$userInfo['user_name']);
        $data['user_id'] = $userInfo['id'];
        $data['user_name'] = $userInfo['user_name'];
        $data['user_type'] = $userInfo['user_type'];
        $data['book_no'] = $this->createBookNum();
        //根据预约人的用户类型获取预约的赠送积分
        $userCateInfo = UserCate::getUserCateInfoByUserType($userInfo['user_type']);
        if (!$userCateInfo){
            Log::error('[用户预约]------用户缺少类型，无法预约--------------用户id：'.$userInfo['id'].'，用户姓名：'.$userInfo['user_name']);
            $this->setErrorMsg('缺少用户类型，请联系管理员,');
            return false;
        }
        Log::info('[用户类型信息]--------userCate：'.json_encode($userCateInfo));
        //根据用户的类型获取用户预约的赠送积分
        $bookScore = $userCateInfo['book_score'];   //预约赠送积分
        $storeScore = $userCateInfo['store_score']; //到店赠送积分
        $finishedScore = $userCateInfo['order_score']; //完成赠送积分
        $data['book_score'] = $bookScore;
        $data['store_score'] = $storeScore;
        $data['finished_score'] = $finishedScore;
        Log::info('[用户预约]--------预约信息：data='.json_encode($data));
        //用户账户积分信息
        $accountInfo = UserStatistic::getAccountDetail($userInfo['id']);
        //开启事务
        DB::beginTransaction();
        try {
            //1.添加预约记录
            $this->book->addBook($data);
            //2.修改用户账户信息
            $userData = [
                'user_id' => $userInfo['id'],
                'score'    => bcadd($accountInfo->score,$userCateInfo->book_score,2),
                'book_num' => bcadd($accountInfo->book_num,1)
            ];
            $this->userStatistic->updateAccout($userData);
            //3.生成积分记录
            $dealNo = $this->getOrderNo("yy");
            $scoreLog = [
                'deal_no' => $dealNo,
                'user_id' => $userInfo['id'],
                'user_name' => $userInfo['user_name'],
                'deal_score' => $userCateInfo->book_score,
                'surplus_score' => bcadd($accountInfo->score,$userCateInfo->book_score,2),
                'deal_type' => 1,
                'remark' => '预约赠送积分'
            ];
            ScoreDeal::create($scoreLog);
            Log::info("【客户预约】-----预约成功，预约人id:".$userInfo['id'].'，姓名:'.$userInfo['user_name']);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            Log::error('【客户预约】-----预约失败，预约人id：'.$userInfo['id'].'姓名:'.$userInfo['user_name'].'，错误信息：$e:'.json_encode($e));
            $this->setErrorMsg($e->getMessage());
            DB::rollBack();
            return false;
        }
    }

    /**
     * 获取预约码
     * @return int|string
     */
    private function createBookNum(){
        $newBookNum = '';
        //获取当天的最后的预约号
        $bookNo = Book::getBookNum();
        if (empty($bookNo)){
            $newBookNum = date('Ymd').'0001';
        }else{
            $newBookNum = intval($bookNo)+1;
        }
        return $newBookNum;
    }
}
