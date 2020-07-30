<?php


namespace App\Http\Controllers\Api\V1;


use App\Http\Requests\Api\PersonValidator;
use App\Http\Service\PersonCateService;
use App\Http\Service\PersonService;
use App\Library\Render;

/**
 * 私人定制控制器
 * Class PersonController
 * @package App\Http\Controllers\Api
 */
class PersonController extends BaseController
{
    //私人定制
    private $personService;
    //私人定制分类
    private $personCateService;

    /**
     * IndexController constructor.
     */
    public function __construct()
    {
        parent:: __construct();
        $this->personService = isset($this->personService) ?: new PersonService();
        $this->personCateService = isset($this->personCateService) ?: new PersonCateService();
    }

    /**
     * 私人定制分类列表
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPersonCateLists(){
        $data = $this->personCateService->getPersonCateLists();
        return Render::success('获取成功',$data);
    }

    /**
     * 私人定制
     * @param PersonValidator $personValidator
     * @return \Illuminate\Http\JsonResponse
     */
    public function addPerson(PersonValidator $personValidator){
        $data = $personValidator->only(['cate_id','person_name','phone','company',
            'ocupation','person_remark','person_price','sales_price']);
        if ($this->personService->addPerson($this->userInfo,$data)){
            return Render::success('定制成功，等待审核');
        }
        return Render::error($this->personService->getErrorMsg());
    }
}
