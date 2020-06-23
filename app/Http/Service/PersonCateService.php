<?php


namespace App\Http\Service;


use App\Models\PersonCate;

/**
 * 私人定制分类服务层
 * Class PersonCateService
 * @package App\Http\Service
 */
class PersonCateService extends BaseSerivce
{
    private $personCate;

    /**
     * PersonCateService constructor.
     */
    public function __construct()
    {
        $this->personCate = isset($this->personCate) ?: new PersonCate();
    }

    /**
     * 私人定制分类列表
     * @return mixed
     */
    public function getPersonCateLists()
    {
        return $this->personCate->getPersonCateLists();
    }


}
