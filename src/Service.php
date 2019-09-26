<?php
namespace zqs\admin;

use think\Service as TPService;

class Service extends TPService
{
    public function boot()
    {
        //加载路由
        $this->loadRoutesFrom(__DIR__.DIRECTORY_SEPARATOR.'route.php');
    }
}
