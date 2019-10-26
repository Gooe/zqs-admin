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
            return redirect(url('admin/login'));
        }
        
        return $next($request);
    }
}