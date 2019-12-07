<?php
namespace zqs\admin\controller;


use zqs\admin\facade\AuthAdmin;

class Index extends Admin
{
    protected $middleware = ['zqs\admin\middleware\Auth'];
    public function index()
    {
        //提示 小圆点
        $param = [];
        $menulist =  AuthAdmin::getSidebar($param);
        halt($menulist);
        return $this->fetch('index');
    }
    public function console()
    {
        return $this->fetch('console');
    }
    
    
    
    
    
    
    
    
    
    
    
}