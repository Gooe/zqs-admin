<?php
namespace zqs\admin\builder;
use think\facade\View;
class Builder
{
    /**
     * @var array 模板参数变量
     */
    protected $vars = [];
    /**
     * 初始化
     */
    public function __construct()
    {
        View::engine()->config(['view_path'=>__DIR__.DIRECTORY_SEPARATOR]);
    }
    
    /**
     * 设置模板变量
     */
    public function setVars($name,$vars)
    {
        $this->vars[$name] = $vars;
        return $this;
    }
    /**
     * 获取模板变量
     * @param string $name
     * @return mixed
     */
    public function getVars($name)
    {
        return $this->vars[$name];
    }
    /**
     * 设置页面标题
     * @param string $title 页面标题
     * @return $this
     */
    public function setPageTitle($title = '')
    {
        if ($title != '') {
            $this->setVars('page_title', trim($title));
        }
        return $this;
    }
    
    /**
     * 设置页面提示
     * @param string $tips 提示信息
     * @param string $type 提示类型：success/info/warning/danger，默认info TODO
     * @return $this
     */
    public function setPageTips($tips = '', $type = '')
    {
        if ($tips != '') {
            $this->setVars('page_tips', trim($tips));
            //$this->vars['tips_type'] = $type != '' ? trim($type) : '';
        }
        return $this;
    }
    
    /**
     * 引入模块js文件
     * @param string $files_name js文件名，多个文件用逗号隔开
     * @param string $module 指定模块
     * @return $this
     */
    public function js($files_name = '', $module = '')
    {
        if ($files_name != '') {
            $this->loadFile('js', $files_name, $module);
        }
        return $this;
    }
    
    /**
     * 引入模块css文件
     * @param string $files_name css文件名，多个文件用逗号隔开
     * @param string $module 指定模块
     * @return $this
     */
    public function css($files_name = '', $module = '')
    {
        if ($files_name != '') {
            $this->loadFile('css', $files_name, $module);
        }
        return $this;
    }
    
    /**
     * 引入css或js文件
     * @param string $type 类型：css/js
     * @param string $files_name 文件名，多个用逗号隔开
     * @param string $module 指定模块
     */
    private function loadFile($type = '', $files_name = '', $module = '')
    {
        if ($files_name != '') {
            $module = $module == '' ? get_admin_name() : $module;
            if (!is_array($files_name)) {
                $files_name = explode(',', $files_name);
            }
            foreach ($files_name as $item) {
                if (strpos($item, '/')) {
                    $this->vars[$type.'_list'][] = '/static/'. $item.'.'.$type;
                } else {
                    $this->vars[$type.'_list'][] = '/static/'. $module .'/'.$type.'/'.$item.'.'.$type;
                }
            }
            $this->vars[$type.'_list'] = array_unique( $this->vars[$type.'_list']);
        }
    }
    /**
     * 创建各种builder的入口
     * @param string $type 构建器名称，'Form', 'Table', 'View' 或其他自定义构建器
     * @return table\Builder|form\Builder|aside\Builder
     * @throws Exception
     */
    public function make($type = '')
    {
        if ($type == '') {
            throw new \think\Exception('未指定构建器名称', 8001);
        } else {
            $type = strtolower($type);
        }
        
        // 构造器类路径
        $class = '\\zqs\\admin\\builder\\'. $type .'\\'.ucfirst($type);
        if (!class_exists($class)) {
            throw new \think\Exception($type . '构建器不存在', 8002);
        }
        
        return new $class;
    }
    
    /**
     * 直接渲染内容
     * @param file $file
     * @return unknown
     */
    public function display($file,$vars=[])
    {
        return View::fetch($file,$vars);
    }
    /**
     * 重写模板输出
     */
    public function fetch($tpl='',$vars=[])
    {
        if (method_exists($this, 'end')) {
            $this->end();
        }
        $vars = array_merge($vars,$this->vars);
        //dump($this->vars);
        
        return View::fetch($tpl,$vars);
        
    }
    
    
    
    
    
}