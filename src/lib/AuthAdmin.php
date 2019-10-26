<?php

namespace zqs\admin\lib;

use zqs\admin\model\Admin;

class AuthAdmin extends Auth
{
    
    protected $requestUri = '';
    protected $breadcrumb = [];
    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function __get($name)
    {
        return session('admin.' . $name);
    }
    
    public function login($username, $password, $keeptime = 0)
    {
        $admin = Admin::where('username',$username)->find();
        if (!$admin)
        {
            return false;
        }
        if ($admin->password != md5(md5($password) . $admin->salt))
        {
            $admin->loginfailure++;
            $admin->save();
            return false;
        }
        $admin->loginfailure = 0;
        $admin->login_time = time();
        $admin->token = Random::uuid();
        $admin->save();
        session("admin", $admin);
        $this->keeplogin($keeptime);
        return true;
    }
    /**
     * 注销登录
     */
    public function logout()
    {
        $admin = Admin::get(intval($this->uid));
        if (!$admin)
        {
            return true;
        }
        $admin->token = '';
        $admin->save();
        session("admin",null);
        cookie("keeplogin",null);
        return true;
    }
    
    /**
     * 自动登录
     * @return boolean
     */
    public function autologin()
    {
        $keeplogin = cookie('keeplogin');
        if (!$keeplogin)
        {
            return false;
        }
        list($id, $keeptime, $expiretime, $key) = explode('|', $keeplogin);
        if ($id && $keeptime && $expiretime && $key && $expiretime > time())
        {
            $admin = Admin::get($id);
            if (!$admin)
            {
                return false;
            }
            //token有变更
            if ($key != md5(md5($id) . md5($keeptime) . md5($expiretime) . $admin->token))
            {
                return false;
            }
            session("admin", $admin);
            //刷新自动登录的时效
            $this->keeplogin($keeptime);
            return true;
        }
        else
        {
            return false;
        }
    }
    
    /**
     * 刷新保持登录的Cookie
     * @param int $keeptime
     * @return boolean
     */
    protected function keeplogin($keeptime = 0)
    {
        if ($keeptime)
        {
            $expiretime = time() + $keeptime;
            $key = md5(md5($this->uid) . md5($keeptime) . md5($expiretime) . $this->token);
            $data = [$this->uid, $keeptime, $expiretime, $key];
            cookie('keeplogin', implode('|', $data),$keeptime);
            return true;
        }
        return false;
    }
    
    public function check($name, $uid = '',$type = 1 ,$mode = 'url', $relation = 'or')
    {
        return parent::check($name, $this->uid, $type, $mode,$relation);
    }
    
    /**
     * 检测当前控制器和方法是否匹配传递的数组
     *
     * @param array $arr 需要验证权限的数组
     */
    public function match($arr = [])
    {
        $request = request();
        $arr = is_array($arr) ? $arr : explode(',', $arr);
        if (!$arr)
        {
            return FALSE;
        }
        // 是否存在
        if (in_array(strtolower($request->action()), $arr) || in_array('*', $arr))
        {
            return TRUE;
        }
        
        // 没找到匹配
        return FALSE;
    }
    
    /**
     * 检测是否登录
     *
     * @return boolean
     */
    public function is_login()
    {
        return session('admin') ? true : false;
    }
    
    /**
     * 获取当前请求的URI
     * @return string
     */
    public function getRequestUri()
    {
        return $this->requestUri;
    }
    
    /**
     * 设置当前请求的URI
     * @param string $uri
     */
    public function setRequestUri($uri)
    {
        $this->requestUri = $uri;
    }
    
    public function getGroups($uid = null)
    {
        $uid = is_null($uid) ? $this->uid : $uid;
        return parent::getGroups($uid);
    }
    
    public function getRuleList($uid = null)
    {
        $uid = is_null($uid) ? $this->uid : $uid;
        return parent::getRuleList($uid);
    }
    
    public function getUserInfo($uid = null)
    {
        $uid = is_null($uid) ? $this->uid : $uid;
        
        return $uid != $this->uid ? Admin::get(intval($uid)) : session('admin');
    }
    
    public function getRuleIds($uid = null)
    {
        $uid = is_null($uid) ? $this->uid : $uid;
        return parent::getRuleIds($uid);
    }
    
    public function isSuperAdmin()
    {
        return in_array('*', $this->getRuleIds()) ? true : false;
    }
    
    /**
     * 获得面包屑导航
     * @param string $path
     * @return array
     */
    public function getBreadCrumb($path = '')
    {
        if ($this->breadcrumb || !$path)
            return $this->breadcrumb;
            $path_rule_id = 0;
            foreach ($this->rules as $rule)
            {
                $path_rule_id = $rule['name'] == $path ? $rule['id'] : $path_rule_id;
            }
            if ($path_rule_id)
            {
                $this->breadcrumb = Tree::instance()->init($this->rules)->getParents($path_rule_id, true);
            }
            return $this->breadcrumb;
    }
    
    /**
     * 获取左侧菜单栏
     *
     * @param array $params URL对应的badge数据
     * @return string
     */
    public function getSidebar($params = [])
    {
        $badgeList = $params;
        // 读取管理员当前拥有的权限节点
        $userRule = $this->getRuleList();
        // 必须将结果集转换为数组
        $ruleList = model('AuthRule')->where('ismenu', 1)->field('id,name,pid,title,icon')->order('weigh', 'desc')->cache("__menu__")->select()->toArray();
        
        foreach ($ruleList as $k => &$v)
        {
            if (!in_array($v['name'], $userRule))
            {
                unset($ruleList[$k]);
                continue;
            }
            $v['href'] = $v['name'];
            $v['badge'] = isset($badgeList[$v['name']]) ? $badgeList[$v['name']] : '';
        }
        
        // 构造菜单数据
        Tree::instance()->init($ruleList);
        //$menu = Tree::instance()->getTreeMenu(0, '<li class="@class"><a href="@url" addtabs="@id" url="@url"><i class="@icon"></i> <span>@title</span> <span class="pull-right-container">@caret @badge</span></a> @childlist</li>', $select_id, '', 'ul', 'class="treeview-menu"');
        $menu = Tree::instance()->getTreeArray(0);
        return $menu;
    }
    
}
