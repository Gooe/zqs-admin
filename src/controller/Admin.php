<?php
declare (strict_types = 1);

namespace zqs\admin\controller;

use think\App;
use think\exception\ValidateException;
use think\Validate;
use think\facade\View;
use zqs\admin\traits\Curd;
use zqs\admin\lib\AuthAdmin;

/**
 * 控制器基础类
 */
abstract class Admin
{
    use \qeq66\think\Jump;
    use Curd;
    /**
     * Request实例
     * @var \think\Request
     */
    protected $request;

    /**
     * 应用实例
     * @var \think\App
     */
    protected $app;

    /**
     * 是否批量验证
     * @var bool
     */
    protected $batchValidate = false;

    /**
     * 控制器中间件
     * @var array
     */
    protected $middleware = ['zqs\admin\middleware\Auth'];

    /**
     * 模型名称
     * @var obj
     */
    protected $model;
    
    /**
     * 无需登录的方法,同时也就不需要鉴权了
     * @var array
     */
    protected $noNeedLogin = [];
    
    /**
     * 无需鉴权的方法,但需要登录
     * @var array
     */
    protected $noNeedRight = [];
    /**
     * 权限控制类
     * @var Auth
     */
    protected $auth = null;
    
    
    /**
     * 构造方法
     * @access public
     * @param  App  $app  应用对象
     */
    public function __construct(App $app)
    {
        $this->app     = $app;
        $this->request = $this->app->request;
        //Auth初始化
        $this->auth = AuthAdmin::instance();
        // 控制器初始化
        $this->initialize();
    }

    // 初始化
    protected function initialize()
    {
        // 判断控制器和方法判断是否有对应权限
        $path = $this->request->pathinfo();
        $path = rtrim($path,'.'.$this->request->ext());//去掉后缀
        $path = substr($path, 0, 1) == '/' ? $path : '/' . $path;
        // 检测是否需要验证登录
        if (!$this->auth->match($this->noNeedLogin)){
            //检测是否登录
            if (!$this->auth->is_login()){
                $this->redirect('xx');
            }
            // 判断是否需要验证权限
            if (!$this->auth->match($this->noNeedRight)){
                
                if (!$this->auth->check($path)){
                    $this->result('',0,'无访问权限','json');
                }
            }
        }
        
        View::assign('admin',session('admin'));
    }

    /**
     * 验证数据
     * @access protected
     * @param  array        $data     数据
     * @param  string|array $validate 验证器名或者验证规则数组
     * @param  array        $message  提示信息
     * @param  bool         $batch    是否批量验证
     * @return array|string|true
     * @throws ValidateException
     */
    protected function validate(array $data, $validate, array $message = [], bool $batch = false)
    {
        if (is_array($validate)) {
            $v = new Validate();
            $v->rule($validate);
        } else {
            if (strpos($validate, '.')) {
                // 支持场景
                list($validate, $scene) = explode('.', $validate);
            }
            $class = false !== strpos($validate, '\\') ? $validate : $this->app->parseClass('validate', $validate);
            $v     = new $class();
            if (!empty($scene)) {
                $v->scene($scene);
            }
        }

        $v->message($message);

        // 是否批量验证
        if ($batch || $this->batchValidate) {
            $v->batch(true);
        }
        $result = $v->check($data);
        if ($result){
            return true;
        }else {
            return ['result'=>false,'msg'=>$v->getError()];
        }
        //return $v->failException(true)->check($data);
    }
    
    /**
     * 模板赋值
     * @param unknown $name
     * @param string $value
     */
    protected function assign($name,$value='')
    {
        View::assign($name,$value);
    }
    
    /**
     * 重写模板输出
     */
    protected function fetch($tpl='',$vars=[])
    {
        //$view = new View($this->app);
        View::engine()->config(['view_path'=>get_view_path()]);
        return View::fetch($tpl,$vars);
        
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    

}
