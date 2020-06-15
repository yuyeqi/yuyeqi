<?php

namespace App\Http\Controllers\Admin;

use App\Library\Render;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;

/**
 * 公共类
 * Class PublicController
 * @package App\Http\Controllers\Admin
 */
class PublicController extends BaseController
{
    /**
     * 登陆展示
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function login(Request $request){
        if ($request->isMethod("post")){
            $post =$request->only(['username','password']);
            //验证数据
            $validator =Validator::make($post,[
                'username' => 'required|max:32',
                'password' => 'required|min:6|max:16',
            ],[
                'username.max' => '用户名或密码错误',
                'password.min' => '用户名或密码错误',
                'password.max' => '用户名或密码错误'
            ]);
            if ($validator->fails()){
                return Render::error($validator->errors()->first());
            }
            //验证信息
            $map = ['status'=>0,
                'account'=>$post['username'],
                'is_delete'=>0];
            $admin = Admin::where($map)->first();
            if ($admin == null){
                return Render::error("用户不存在,请联系管理员");
            }
            if ($post['password'] !== Crypt::decrypt($admin->password)){
                return Render::error("用户名或密码错误");
            }
            //存储登陆信息到session
            session(['admin' => $admin->toArray()]);
            //更新登陆信息
            $admin->is_login = 1;
            $admin->login_time = time();
            if($admin->save()){
                return Render::success('success',$admin);
            }
            return Render::error('登陆失败');
        }else{
            return  view('admin.public.login');
        }
    }

    /**
     * 推出登陆
     * @return \Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function loginOut(Request $request){
        $request->session()->forget('admin');
        return redirect('public/login')->with('msg','退出成功');
    }
}
