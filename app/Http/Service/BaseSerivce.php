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


}
