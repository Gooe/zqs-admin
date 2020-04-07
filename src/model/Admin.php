<?php

namespace zqs\admin\model;

use think\Model;
use zqs\admin\lib\Random;

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
    public static function encryptPassword($password, $salt = '', $encrypt = 'md5')
    {
        return $encrypt($encrypt($password) . $salt);
    }
    
    
    public function getLoginTimeAttr($value){
        if ($value)
            $value = date('Y-m-d H:i:s',$value);
        else 
            $value = '从未登录';
        return $value;
    }
    /**
     * 新增前的操作
     */
    public static function onBeforeInsert($admin)
    {
        $admin->salt = Random::alnum(6);
        $admin->password = self::encryptPassword($admin->password,$admin->salt);
    }
    
    /**
     * 新增后的操作
     */
    public static function onAfterInsert($admin)
    {
        self::saveGroupAll($admin);
    }
    /**
     * 查询后
     */
    public static function onAfterRead($admin)
    {
        //只针对后台操作
        if (!empty($admin->childrenIds)){
            $admin->password = '';
        }
        
    }
    
    /**
     * 更新前
     */
    public static function onBeforeUpdate($admin)
    {
        //只针对后台操作
        if (!empty($admin->childrenIds)){
            //先移除所有权限
            AuthGroupAccess::where('uid',$admin->id)->delete();
            //是否修改密码
            if (!empty($admin->password)){
                $admin->password = self::encryptPassword($admin->password,$admin->salt);
            }else {
                unset($admin->password);
            }
        }
        
    }
    /**
     * 更新后
     */
    public static function onAfterUpdate($admin)
    {
        self::saveGroupAll($admin);
    }
    
    /**
     * 批量进组,后台新增和修改后
     */
    public static function saveGroupAll($admin)
    {
        if (!empty($admin->childrenIds)){
            //过滤不允许的组别,避免越权
            $group = array_intersect($admin->childrenIds, str2arr($admin->group));
            $dataset = [];
            foreach ($group as $value)
            {
                $dataset[] = ['uid' => $admin->uid, 'group_id' => $value];
            }
            $aga = new AuthGroupAccess();
            $aga->saveAll($dataset);
        }
        
    }
    
    

}
