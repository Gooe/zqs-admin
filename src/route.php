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

//需要登录的,为配合后台非路由，此处已取消验证
Route::group('', function () {
    //Route::get('//', 'zqs\admin\controller\Index@index');
    Route::get('', 'zqs\admin\controller\Index@index');
    Route::get('index', 'zqs\admin\controller\Index@index');
    Route::get('console', 'zqs\admin\controller\Index@console');
    //权限
    Route::rule('auth/rule/:action','zqs\admin\controller\auth\Rule@:action');
    Route::rule('auth/adminuser/:action','zqs\admin\controller\auth\Adminuser@:action');
    Route::rule('auth/group/:action','zqs\admin\controller\auth\group@:action');
    //资料
    Route::rule('personal/:action','zqs\admin\controller\Personal@:action');
    //配置
    Route::rule('config/:action','zqs\admin\controller\Config@:action');
    //cms
    Route::rule('cms/attach/:action','zqs\admin\controller\cms\Attach@:action');
    Route::rule('cms/cate/:action','zqs\admin\controller\cms\cate@:action');
    Route::rule('cms/article/:action','zqs\admin\controller\cms\article@:action');
    
});//->middleware(\zqs\admin\middleware\Auth::class);


//无需验证的方法
Route::group('', function () {
    //退登与退出
    Route::get('login', 'zqs\admin\controller\Login@index');
    Route::post('login', 'zqs\admin\controller\Login@do_login');
    Route::get('logout', 'zqs\admin\controller\Login@logout');
    //上传
    Route::post('upload/image', 'zqs\admin\controller\Upload@image');
    Route::post('upload/file', 'zqs\admin\controller\Upload@file');
});

Route::get('serach', function () {
    return "搜索内容：".input('keywords')."<br/>请在后台应用路由配置目录，重置search路由 ";
});






















