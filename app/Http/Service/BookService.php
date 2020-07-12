<?php


namespace App\Http\Service;

use App\Models\Book;

/**
 * 报备服务层
 * Class SlideshowService
 * @package App\Http\Service
 */
class BookService extends BaseSerivce
{
    private $book;

    /**
     * SlideshowService constructor.
     */
    public function __construct()
    {
        $this->book = isset($this->book) ?: new Book();
    }

    /**
     * 报备列表
     * @return int
     */
    public function getBookList(){
        return $this->book->getBookList();
    }

    /**
     * 后台轮播图列表
     * @param int $limit
     */
    public function getSlideshowAdminLists(String $keyword,int $limit)
    {
        return $this->book->getBookAdminLists($keyword,$limit);
    }

    /**
     * 添加轮播图
     * @param array $data
     * @param $loginInfo
     * @return mixed
     */
    public function addSlideshow(array $data, $loginInfo)
    {
        $data['create_user_id'] = $loginInfo['id'];
        $data['create_user_name'] = $loginInfo['username'];
        $data['update_user_id'] = $loginInfo['id'];;
        $data['update_user_name'] = $loginInfo['username'];
        return $this->slideshow->addSlideshow($data);
    }

    /**
     * 后台轮播详情
     * @param $id
     * @return mixed
     */
    public function getAdminSlideshowById($id)
    {
        return $this->slideshow->getAdminSlideshowById($id);
    }

    /**
     * 修改轮播图
     * @param array $data
     * @param $loginInfo
     * @return mixed
     */
    public function editSlideshow(array $data, $loginInfo)
    {
        $data['update_user_id'] = $loginInfo['id'];;
        $data['update_user_name'] = $loginInfo['username'];
        return $this->slideshow->editSlideshow($data);
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
        return  $this->slideshow->delBatch($data,$ids);
    }

    /**
     * 修改新闻状态
     * @param array $data
     * @param $loginInfo
     * @return mixed
     */
    public function updateStatus(array $data, $loginInfo)
    {
        $data['update_user_id'] = $loginInfo['id'];;
        $data['update_user_name'] = $loginInfo['username'];
        return $this->slideshow->updateStatus($data);
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
}
