<?php
namespace zqs\admin\controller;

class Login extends Admin
{
    public function index()
    {
        
        return $this->fetch('login');
    }
    /**
     * 管理员登录
     */
    public function do_login()
    {
        $url = $this->request->get('url', 'index/index');
        /* if ($this->auth->isLogin()){
            return redirect($url);
        } */
        if ($this->request->isPost()){
            $username = $this->request->post('username');
            $password = $this->request->post('password');
            $verifycode = $this->request->post('verifycode');
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
                
                return 'xxx';
                //$this->error($result->getError(), $url, ['token' => $this->request->token()]);
            }
            
            $result = $this->auth->login($username, $password, $keeplogin ? 7*86400 : 0);
            if ($result === true){
                $this->success('登录成功', $url, ['url' => $url]);
                return;
            }else{
                $this->error('登录或密码错误', $url, ['token' => $this->request->token()]);
            }
            return;
        }
        // 根据客户端的cookie,判断是否可以自动登录
        if ($this->auth->autologin()){
            $this->redirect($url);
        }
        $this->view->assign('title', '管理登录');
        return $this->view->fetch();
    }
}