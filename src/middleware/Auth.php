<?php
namespace zqs\admin\middleware;

use zqs\admin\facade\AuthAdmin;

class Auth
{
    public function handle($request, \Closure $next)
    {
        $check = AuthAdmin::is_login();
        
        if (! $check) {
            //session('error_msg', '请先登录系统');
            
            return redirect(url('admin/login'));
        }
        
        return $next($request);
    }
}