<?php


namespace App\Http\Service;

use Illuminate\Support\Facades\Request;

class BaseSerivce
{
    /**
     * @return int
     */
    public function getErrorCode(): int
    {
        return $this->errorCode;
    }

    /**
     * @param int $errorCode
     */
    public function setErrorCode(int $errorCode): void
    {
        $this->errorCode = $errorCode;
    }

    /**
     * @return string
     */
    public function getErrorMsg(): string
    {
        return $this->errorMsg;
    }

    /**
     * @param string $errorMsg
     */
    public function setErrorMsg(string $errorMsg): void
    {
        $this->errorMsg = $errorMsg;
    }

    //错误消息码
    protected $errorCode = 0;

    //错误消息
    protected $errorMsg  = '';
    /**
     * BaseSerivce constructor.
     * @param null $loginInfo
     */
    public function __construct()
    {

    }

    /**
     * 处理分页数据
     * @param $pageData
     * @return array
     */
    public function getPageData($pageData){
        $data = [];
        $data['data'] = $pageData->items();
        $data['total'] = $pageData->total();
        return $data;
    }

    /**
     * @return string生成订单号
     */
    protected function getOrderNo($prifix){
        $orderNo = date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
        return $prifix.$orderNo;
    }
}
