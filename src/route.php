<?php
use think\facade\Route;
use think\facade\Config;
$admin_path = Config::get('admin.admin_path')?:'admin';

Route::group($admin_path, function () {
    //Route::get('//', 'zqs\admin\controller\Index@index');
    Route::get('', 'zqs\admin\controller\Index@index');
    Route::get('console', 'zqs\admin\controller\Index@console');
})->middleware(\zqs\admin\middleware\Auth::class);


//无需验证的方法
Route::group($admin_path, function () {
    Route::get('login', 'zqs\admin\controller\Login@index');
});