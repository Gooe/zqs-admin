<?php
declare (strict_types = 1);

namespace zqs\admin\controller;

use think\App;
use think\exception\ValidateException;
use think\Validate;
use think\View;

/**
 * 控制器基础类
 */
abstract class Admin
{
    use \qeq66\think\Jump;
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
     * 构造方法
     * @access public
     * @param  App  $app  应用对象
     */
    public function __construct(App $app)
    {
        $this->app     = $app;
        $this->request = $this->app->request;

        // 控制器初始化
        $this->initialize();
    }

    // 初始化
    protected function initialize()
    {}

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
     * 重写模板输出
     */
    protected function fetch($tpl='',$vars=[])
    {
        $view = new View($this->app);
        $view->engine()->config(['view_path'=>get_view_path()]);
        return $view->fetch($tpl,$vars);
        
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    

}
