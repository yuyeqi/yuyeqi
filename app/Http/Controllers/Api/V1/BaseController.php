<?php


namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;

class BaseController extends Controller
{

    //登陆人id
    protected $userInfo = [
        "id" => 1,
        "user_name" => "何怡鸣",
        "phone" => '123456789',
        "user_type" => 1
    ];
}
