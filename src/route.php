<?php
use think\facade\Route;

//后台应用目录
$admin_path = get_admin_path();

//当前应用名称
$app_name = app('http')->getName();

//只绑定admin应用
if ($app_name != $admin_path){
    return ;
}

//需要登录的
Route::group('', function () {
    //Route::get('//', 'zqs\admin\controller\Index@index');
    Route::get('', 'zqs\admin\controller\Index@index');
    Route::get('index', 'zqs\admin\controller\Index@index');
    Route::get('console', 'zqs\admin\controller\Index@console');
    //Route::resource('menu', 'zqs\admin\controller\Menu');
    Route::rule('auth/rule/:action','zqs\admin\controller\auth\Rule@:action');
    Route::rule('auth/adminuser/:action','zqs\admin\controller\auth\Adminuser@:action');
    Route::rule('auth/group/:action','zqs\admin\controller\auth\group@:action');
});//->middleware(\zqs\admin\middleware\Auth::class);


//无需验证的方法
Route::group('', function () {
    Route::get('login', 'zqs\admin\controller\Login@index');
    Route::post('login', 'zqs\admin\controller\Login@do_login');
});

