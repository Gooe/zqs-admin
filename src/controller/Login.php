<?php
namespace zqs\admin\controller;

use zqs\admin\facade\AuthAdmin;

class Login extends Admin
{
    protected $noNeedLogin = ['*'];
    public function index()
    {
        
        $url = $this->request->get('url', '/'.get_admin_name().'/index');
        
        // 根据客户端的cookie,判断是否可以自动登录
        if (AuthAdmin::autologin()){
            return redirect($url);
        }
        //已经登录
        if (AuthAdmin::is_login()){
            return redirect($url);
        }
        
        
        $this->assign('title', '管理登录');
        return $this->fetch('login');
    }
    /**
     * 管理员登录
     */
    public function do_login()
    {
        $url = $this->request->get('url', '/'.get_admin_name().'/index');
        if ($this->request->isPost()){
            $username = $this->request->post('username');
            $password = $this->request->post('password');
            $verifycode = $this->request->post('vercode');
            $keeplogin = $this->request->post('keeplogin');
            $token = $this->request->post('__token__');
            $rule = [
                'username'  => 'require|length:2,30',
                'password'  => 'require|length:6,30',
                'verifycode'=> 'require|captcha',
                '__token__' => 'token',
            ];
            $data = [
                'username'  => $username,
                'password'  => $password,
                'verifycode'=> $verifycode,
                '__token__' => $token,
            ];
            $msg = [
                'username.require' => '请输入用户名',
                'username.length'  => '用户名长度在2-30个字符之间',
                'password.require' => '请输入密码',
                'password.length'  => '密码长度在6-30个字符之间',
                'verifycode.require' => '请输入验证码',
                'verifycode.captcha' => '验证码不正确',
            ];
            
            $result = $this->validate($data, $rule,$msg);
            if ($result!==true){
                $this->error($result['msg'], $url, ['token' => token()]);
            }
            $result = AuthAdmin::login($username, $password, $keeplogin ? 7*86400 : 0);
            if ($result === true){
                $this->success('登录成功', $url, ['url' => $url]);
                return;
            }else{
                $this->error('登录或密码错误', $url, ['token' => token()]);
            }
            return;
        }
        
    }
}