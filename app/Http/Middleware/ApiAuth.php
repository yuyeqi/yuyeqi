<?php


namespace App\Http\Middleware;

use App\Library\Render;
use Closure;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class ApiAuth extends BaseMiddleware
{
    public function handle($request,Closure $next,$guard = 'api'){
        //在排除黑名单，比如登陆
        if ($request->is(...$this->except)){
            return $next($request);
        }
        try {
            $this->checkForToken($request);//是否携带令牌
            if ($this->auth->parseToken()->authenticate()) {
                return $next($request); //验证通过
            }
            return Render::json(401,'登陆失效，请重新登陆');
        } catch (JWTException $e) {
            /*//如果token过期
            if($e instanceof TokenExpiredException){
                try {// 尝试刷新 如果成功 返给前端 关于前端如何处理的 看前边 index.js
                    $token = $this->auth->refresh();
                    return Render::json(4011, $e->getMessage(), ['access_token' => $token]);
                } catch (\Exception $e) {
                    //达到刷新上限
                    return Render::json(401,$e->getMessage());
                }
            }else{
                //直接返回
                return Render::json(401,$e->getMessage());
            }*/
            return Render::json(401,$e->getMessage());
        }
    }

    protected $except = [
        'v1/public/login',
    ];
}
