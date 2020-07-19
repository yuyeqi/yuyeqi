<?php


namespace App\Http\Service;

use App\Models\Book;
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
        if (!$userInfo){
            Log::error('[用户预约]------用户缺少类型，无法预约--------------用户id：'.$userInfo['id'].'，用户姓名：'.$userInfo['user_name']);
            $this->setErrorMsg('缺少用户类型，请联系管理员,');
            return false;
        }
        Log::info('[用户类型信息]--------userCate：'.json_encode($userCateInfo));
        //根据用户的类型获取用户预约的赠送积分
        $bookScore = $userCateInfo['book_score'];   //预约赠送积分
        $storeScore = $userCateInfo['store_score']; //到店赠送积分
        $data['book_score'] = $bookScore;
        $data['store_score'] = $storeScore;
        Log::info('[用户预约]--------预约信息：data='.json_encode($data));
        //开启事务
        DB::beginTransaction();
        try {
            //1.添加预约记录
            $this->book->addBook($data);
            //2.修改预约用户预约记录数
            $this->userStatistic->updateUserCount($userInfo['id'],'book_num');
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
