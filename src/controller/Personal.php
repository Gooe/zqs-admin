<?php
namespace zqs\admin\controller;
use zqs\admin\facade\Builder;
use zqs\admin\model\Admin as AdminModel;
use zqs\admin\lib\Random;
use think\facade\Cache;


class Personal extends Admin
{
    /**
     * 验证器
     */
    public $validate_class = '\zqs\admin\validate\Admin.info';
    /**
     * 无需验证
     * @var array
     */
    protected $noNeedRight = ['*'];
    public function initialize()
    {
        parent::initialize();
        $this->model = new AdminModel();
        $this->form = Builder::make('form')
                                ->setPageTitle('个人资料')
                                ->addFormItems([
                                    ['input','用户名','username','','text','req','不可更改','','disabled'],
                                    ['input','昵称','nickname'],
                                    ['input','邮箱','email'],
                                    ['image','图像','headimg',$this->auth->headimg],
                                    //['input','密码','password','','text','','不填不更改'],
                                ])
                                ->setVars('value_fields','nickname,username,email');
    }
    /**
     * 资料修改
     */
    public function info()
    {
        //禁止直接修改密码
        $data = [
            'nickname' => input('nickname'),
            'email' => input('email'),
            'headimg' => input('headimg'),
        ];
        $re =  parent::edit($this->auth->uid);
        //因为个人资料面板读取的Session显示，修改自己资料后同时更新Session
        $admin = session('admin');
        $admin_id = $admin ? $admin->uid : 0;
        if($this->auth->uid==$admin_id){
            $admin = $this->model->find($admin_id);
            session("admin", $admin);
        }
        return $re;
    }
    
    /**
     * 修改密码
     */
    public function password()
    {
        if ($this->request->isAjax()){
            $info = $this->model->find($this->auth->uid);
            $oldpwd = input('post.oldpassword');
            $newpwd = input('post.newpassword');
            $renewpwd = input('post.renewpassword');
            if ($newpwd!==$renewpwd){
                $this->error('两次密码输入的不一致！');
            }
            if (strlen($newpwd)<6 || strlen($newpwd)>20){
                $this->error('密码长度在6-20位！');
            }
            if ($info->password != md5(md5($oldpwd) . $info->salt)){
                $this->error('当前密码错误~');
            }
            //开始修改密码
            $salt = Random::alnum();
            $info->salt = $salt;
            $info->password = md5(md5($newpwd) . $salt);
            if ($info->save()){
                $this->success('密码修改成功');
            }else{
                $this->error('密码修改失败');
            }
        }
        return Builder::make('form')
                    ->setPageTitle('修改密码')
                    ->setDataUrl('password')
                    ->addFormItems([
                        ['input','当前密码','oldpassword','','password','required','&nbsp;'],
                        ['input','新密码','newpassword','','password','required','6-12个字符'],
                        ['input','新密码','renewpassword','','password','required','再输入一次'],
                    ])
                    ->setVars('value_fields','nickname,username,email')
                    ->fetch();
       
    }
    
    /**
     * 清除缓存
     */
    public function clear_cache()
    {
        Cache::clear();
        return $this->success('清除成功');
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}