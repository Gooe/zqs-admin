<?php
namespace zqs\admin\validate;
use think\Validate;

class Admin extends Validate
{
    
    protected $rule = [
        'username|用户名' => 'require|unique:admin|length:2,30',
        'nickname|昵称'  => 'require',
        'email|邮箱'     =>  'email',
        'group|角色组'    => 'require',
        'password|密码'  => 'require|length:6,30',
    ];
    
    
    protected $scene = [
        'add'  =>  ['username','email','group','password','nickname'],
        'edit' => ['email','group','password'],//此处已无效，与下面等同
    ];  
    
    // edit 验证场景定义
    public function sceneEdit()
    {
        return $this->only(['email','group','nickname']);
    }
    
    // info 验证场景定义
    public function sceneInfo()
    {
        return $this->only(['email','nickname']);
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}