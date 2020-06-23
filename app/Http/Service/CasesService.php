<?php


namespace App\Http\Service;


use App\Models\Cases;

/**
 * 案例服务层
 * Class CasesService
 * @package App\Http\Service
 */
class CasesService extends BaseSerivce
{
    private $cases;

    /**
     * CasesService constructor.
     */
    public function __construct()
    {
        $this->cases = isset($this->cases) ?: new Cases();
    }

    /*----------------------------------小程序----------------------------*/
    /**
     * 小程序案例列表
     * @return mixed
     */
    public function getCasesLists($limit){
        $pageData = $this->cases->getCaseLists($limit);
        return $this->getPageData($pageData);
    }

    /**
     * 案例详情
     * @param $id
     * @return mixed
     */
    public function getCasesDetail($id)
    {
        return $this->cases->getCasesDetail($id);
    }

}
