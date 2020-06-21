<?php


namespace App\Http\Service;

use App\Models\Slideshow;

/**
 * 轮播图服务层
 * Class SlideshowService
 * @package App\Http\Service
 */
class SlideshowService extends BaseSerivce
{
    private $slideshow;

    /**
     * SlideshowService constructor.
     */
    public function __construct()
    {
        $this->slideshow = isset($this->slideshow) ?: new Slideshow();
    }

    /**
     * 轮播图列表
     * @return int
     */
    public function getSlideshowList(){
        return $this->slideshow->getSlideshowLists();
    }
}
