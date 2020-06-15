<?php

namespace App\Http\Middleware;

use Closure;

class AdminLogin
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
        $this->loginInfo = session('admin');
        if ( $this->loginInfo == null){
            return redirect('public/login');
        }
        return $next($request);
    }
}
