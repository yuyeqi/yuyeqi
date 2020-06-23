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
