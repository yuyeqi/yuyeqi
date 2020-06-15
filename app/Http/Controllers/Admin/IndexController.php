<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndexController extends BaseController
{
    //后台首页
    public function index(){
        return view('admin.index.index');
    }

    //后台欢迎页
    public function welcome(){
        return view('admin.index.welcome');
    }
}
