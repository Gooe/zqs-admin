<?php
namespace zqs\admin\validate;
use think\Validate;

class Article extends Validate
{
    
    protected $rule = [
        'title|标题' => 'require|length:0,150',
        'cate_id|分类'  => 'require',
    ];
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}