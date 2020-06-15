<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    //登录用户信息
    protected $loginInfo = null;

    /**
     * BaseController constructor.
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->loginInfo = $request->session()->get('admin');
            return $next($request);
        });
    }
}
