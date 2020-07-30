<?php


namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;
use http\Env\Request;

class BaseController extends Controller
{

    //登陆人id
    protected $userInfo;
    /**
     * BaseController constructor.
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->userInfo = $request->get('user');
            return $next($request);
        });
    }

}
