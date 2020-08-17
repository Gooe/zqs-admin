<?php
namespace zqs\admin\validate;
use think\Validate;

class Config extends Validate
{
    
    protected $rule = [
        'name|变量名' => 'require|unique:config|alphaDash',
        'title|标题'  => 'require',
        'type|类型'    => 'require',
    ];
    
    
    protected $scene = [
        'edit' => ['title','type'],
    ];
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}