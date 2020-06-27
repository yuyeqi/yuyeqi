<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //未登录的，登录
        if (!Auth::guard('admin')->check()) {
            return redirect(route('login'));
        }
        $user = Auth::guard('admin')->user()->toArray();
        $request->attributes->add(['admin'=>$user]);
        return $next($request);
    }
}
