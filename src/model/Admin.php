<?php

namespace zqs\admin\model;

use think\Model;

class Admin extends Model
{
    protected $pk = 'uid';
    /**
     * 重置用户密码
     * @author baiyouwen
     */
    public function resetPassword($uid, $NewPassword)
    {
        $passwd = $this->encryptPassword($NewPassword);
        $ret = $this->where(['uid' => $uid])->update(['password' => $passwd]);
        return $ret;
    }

    // 密码加密
    protected function encryptPassword($password, $salt = '', $encrypt = 'md5')
    {
        return $encrypt($password . $salt);
    }
    public function getStatusAttr($value)
    {
        $status = [
            -1=>'<span class="layui-badge layui-bg-gray">删除</span>',
            0=>'<span class="layui-badge">禁用</span>',
            1=>'<span class="layui-badge layui-bg-green">正常</span>',
            2=>'<span class="layui-badge layui-bg-orange">审核</span>'
        ];
        return $status[$value];
        
    }
    
    public function getLoginTimeAttr($value){
        if ($value)
            $value = date('Y-m-d H:i:s',$value);
        else 
            $value = '从未登录';
        return $value;
    }

}
