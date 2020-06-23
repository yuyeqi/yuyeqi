<?php


namespace App\Http\Service;


use App\Models\Person;

/**
 * 私人定制服务层
 * Class PersonService
 * @package App\Http\Service
 */
class PersonService extends BaseSerivce
{
    private $person;

    /**
     * PersonService constructor.
     */
    public function __construct()
    {
        $this->person = isset($this->person) ?: new Person();
    }


    /**
     * 提交私人定制信息
     * @param array $data
     * @return mixed
     */
    public function addPerson(array $data)
    {
        //每月只能提交一次
        //dd($this->person->getMonthPerson($data['user_id']));
        if ($this->person->getMonthPerson($data['user_id']) > 0){
            $this->setErrorMsg('你本月已经提交过定制计划');
            return false;
        }
        return $this->person->addPerson($data);
    }


}
