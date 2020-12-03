<?php


namespace App\Http\Middleware;

use App\Library\Render;
use App\Models\User;
use Closure;

class ApiAuth
{
    public function handle($request,Closure $next){
        //根据token获取用户信息
        $token = $request->header('token');
        if(empty($token)){
            return  Render::error('登陆失效',"401");
        }
        //获取用户信息
        $userInfo = User::getUserBytoken($token);
        if (!$userInfo){
            return  Render::error('未登录','403');
        }
        $request->attributes->add(['user'=>$userInfo->toArray()]);
        return $next($request);
    }

    protected $except = [
        'v1/public/login',
    ];
}
