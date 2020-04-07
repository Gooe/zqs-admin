<?php
namespace zqs\admin\middleware;

use zqs\admin\facade\AuthAdmin;

class Auth
{
    public function handle($request, \Closure $next)
    {
        //是否登录
        $check = AuthAdmin::is_login();
        if (! $check) {
            return redirect(url('/'.get_admin_name().'/login'));
        }
        
        return $next($request);
    }
}