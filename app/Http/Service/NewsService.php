<?php


namespace App\Http\Service;


use App\Models\News;

/**
 * 新闻服务层
 * Class NewsService
 * @package App\Http\Service
 */
class NewsService extends BaseSerivce
{
    private $news;

    /**
     * NewsService constructor.
     */
    public function __construct()
    {
        $this->news = isset($this->news) ?: new News();
    }

    /**
     * 后台新闻列表
     * @param string $keyword
     * @param int $limit
     * @return array
     */
    public function getAdminNewsLists(string $keyword, int $limit)
    {
        return $this->news->getAdminNewsLists($keyword,$limit);
    }

    /**
     * 添加新闻
     * @param array $data
     * @param $loginInfo
     * @return mixed///
     */
    public function addNews(array $data, $loginInfo)
    {
        $data['create_user_id'] = $loginInfo['id'];
        $data['create_user_name'] = $loginInfo['username'];
        $data['update_user_id'] = $loginInfo['id'];;
        $data['update_user_name'] = $loginInfo['username'];
        return $this->news->addNews($data);
    }

    /**
     * 修改新闻
     * @param array $data
     * @param $loginInfo
     * @return mixed
     */
    public function editNews(array $data, $loginInfo)
    {
        $data['update_user_id'] = $loginInfo['id'];;
        $data['update_user_name'] = $loginInfo['username'];
        return $this->news->editNews($data);
    }

    /**
     * 后端新闻详情
     * @param $id
     * @return mixed
     */
    public  function getAdminNewsById($id){
        return News::getNewsDetail($id);
    }

    /**
     * 删除新闻
     * @param array $ids
     * @param $loginInfo
     * @return mixed
     */
    public function delBatch(array $ids,$loginInfo)
    {
        $data['update_user_id'] = $loginInfo['id'];;
        $data['update_user_name'] = $loginInfo['username'];
        $data['is_delete'] = 1;
        return  $this->news->delBatch($data,$ids);
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
        return $this->news->updateStatus($data);
    }

    /**
     * 修改推荐状态
     * @param array $data
     * @param $loginInfo
     * @return mixed
     */
    public function updateIsRecommend(array $data,$loginInfo){
        $data['update_user_id'] = $loginInfo['id'];;
        $data['update_user_name'] = $loginInfo['username'];
        return $this->news->updateIsRecommend($data,$loginInfo);
    }
    /*------------------------------------------小程序---------------------------------*/
    /**
     * 小程序新闻列表
     * @return mixed
     */
    public function getNewsLists(){
        return $this->news->getNewsLists();
    }

    /**
     * 小程序新闻分页列表
     * @param $limit
     * @return array
     */
    public function getNewsPageLists($limit)
    {
        $pageData = $this->news->getNewsPageLists($limit);
        return $this->getPageData($pageData);
    }

    /**
     * 小程序新闻详情
     * @param $id
     * @return mixed
     */
    public function getNewsDetail($id)
    {
        return News::getNewsDetail($id);
    }


}
