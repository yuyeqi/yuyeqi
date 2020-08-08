<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Service\AdminService;
use App\Library\Render;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

/**
 * 公共类
 * Class PublicController
 * @package App\Http\Controllers\Admin
 */
class PublicController extends Controller
{
    private $adminService;
    const   PHTHURL = 'admin'; //上传文件路径
    /**
     * PublicController constructor.
     * @param $adminService
     */
    public function __construct()
    {
        $this->adminService = isset($this->adminService) ?: new AdminService();
    }


    /**
     * 登陆展示
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function login(Request $request){
        if ($request->isMethod("post")){
            $data =$request->only(['account','password']);
            //验证数据
            $validator =Validator::make($data,[
                'account' => 'required|max:32',
                'account' => 'required|min:6|max:16',
            ],[
                'account.max' => '用户名或密码错误',
                'password.min' => '用户名或密码错误',
                'password.max' => '用户名或密码错误'
            ]);
            if ($validator->fails()){
                return Render::error($validator->errors()->first());
            }
            //验证信息
            $data['status'] = 0;
            $data['is_delete'] = 0;
            $loginStatus = Auth::guard('admin')->attempt($data);
            if (!$loginStatus){
                return Render::error("用户名或密码错误");
            }
            //更新登陆信息
            $admin = $this->adminService->getAdminByAcount($data['account']);
            $admin->is_login = 1;
            $admin->login_time = date('Y-m-d H:m:s');
            //生成登陆token
            if($admin->save()){
                return Render::success('success');
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

    /**
     * 后台上传图片
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadAdmin(Request $request){
        $file = $request->file('file');
        $baseUrl = 'http://hpmc.oss-cn-beijing.aliyuncs.com/';
        if ($file && $file->isValid()) {
            // 获取文件相关信息
            $ext = $file->getClientOriginalExtension();     // 扩展名
            $realPath = $file->getRealPath();   //临时文件的绝对路径
            $type = $file->getClientMimeType();     // image/jpeg
            $size =$file->getSize();
            if($size > 4*1024*1024){
                return Render::error('文件大小超过4M');
            }
            $extArr = array('jpg','jpeg','png','gif');
            if(!in_array($ext,$extArr)){
                return Render::error('文件格式不正确');
            }
            $bool = Storage::put(self::PHTHURL, $file);
            if ($bool){
                $url = $baseUrl.$bool;
                return Render::success('上传成功',$url);
            }else{
                return  Render::error('上传失败');
            }
        }
        return  Render::error('上传失败');
    }
}
