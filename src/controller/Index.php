<?php
namespace zqs\admin\controller;


use zqs\admin\facade\AuthAdmin;

class Index extends Admin
{
    //protected $middleware = ['zqs\admin\middleware\Auth'];
    protected $noNeedRight = ['*'];//只能都不验证
    /**
     * 初始化
     * {@inheritDoc}
     * @see \zqs\admin\controller\Admin::initialize()
     */
    protected function initialize()
    {
        parent::initialize();
    }
    
    public function index()
    {
        //提示 小圆点
        $param = [];
        $menulist =  AuthAdmin::getSidebar($param);
        //halt($menulist);
        $vars = [
            'menulist' => $menulist,
        ];
        
        return $this->fetch('index',$vars);
    }
    public function console()
    {
        return $this->fetch('console');
    }
    
    
    
    
    
    
    
    
    
    
    
}