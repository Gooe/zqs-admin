<?php
namespace zqs\admin\controller;


class Index extends Admin
{
    protected $middleware = ['zqs\admin\middleware\Auth'];
    public function index()
    {
        
        return $this->fetch('index');
    }
    public function console()
    {
        return $this->fetch('console');
    }
    
    
    
    
    
    
    
    
    
    
    
}