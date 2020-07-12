<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

class IndexController extends BaseController
{

    //后台首页
    public function index(Request $request){
        return view('admin.index.index');
    }

    //后台欢迎页
    public function welcome(){
        return view('admin.index.welcome');
    }
}
