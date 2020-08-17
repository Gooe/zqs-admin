<?php
namespace zqs\admin\builder;
use think\facade\View;
class Builder
{
    /**
     * @var array 公共模板参数变量
     */
    protected $vars = [
        'data_url'          => '',//数据url
        'page_title'        => '',//页面标题
        'page_tips'         => '',//页面提示
        'tab_nav'           => [],//选项卡
        'js_list'           => [],// js文件列表
        'css_list'          => [],// css列表
        'layui_modules'     => [],//所使用的layui模块
        'layui_modules_js'  => [],//额外的layui js代码片段
        'extra_html'        => '',// 额外HTML代码
        'extra_css'         => '',// 额外CSS代码 TODO
    ];
    /**
     * 初始化
     */
    public function __construct()
    {
        View::engine()->config(['view_path'=>__DIR__.DIRECTORY_SEPARATOR]);
    }

    /**
     * 设置模板变量
     * @param $name
     * @param $vars
     * @return $this
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
     * 设置数据url
     * @param $url
     * @return $this
     */
    public function setDataUrl($url)
    {
        $this->vars['data_url'] = $url;
        return $this;
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
     * 设置Tab按钮列表
     * @param array $tab_list Tab列表 如：['tab1' => ['title' => '标题', 'url' => 'http://www.baidu.com']]
     * @param string $curr_tab 当前tab名
     * @return $this
     */
    public function addTabNav($tab_list = [], $curr_tab = '')
    {
        if (!empty($tab_list)) {
            $this->vars['tab_nav'] = [
                'tab_list' => $tab_list,
                'curr_tab' => $curr_tab,
            ];
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
     * 添加layui module
     * @param string $name
     * @return $this
     */
    public function addLayuiModules($name)
    {
        $this->vars['layui_modules'][] = $name;
        $this->vars['layui_modules'] = array_unique($this->vars['layui_modules']);
        return $this;
    }

    /**
     * 添加额外的js代码片段
     * @param string $name 文件名或绝对路径
     * @param array $vars 传入的变量
     * @return $this
     */
    public function addLayuiModulesJs($name,$vars=[])
    {
        $this->vars['layui_modules_js'][] = [
            'name' => $name,
            'vars' => $vars,
        ];
        return $this;
    }


    /**
     * 设置额外代码
     * @param string $extra_html 额外代码
     * @param string $tag 标记位置 block_top|page_tips_top|page_tips_bottom|table_top/form_top|table_bottom/form_bottom|block_bottom
     * @return $this
     */
    public function addExtraHtml($extra_html = '', $tag = '')
    {
        if ($extra_html != '') {
            $tag != '' && $tag = '_'.$tag;
            $this->vars['extra_html'.$tag] = $extra_html;
        }
        return $this;
    }



    /**
     * 创建各种builder的入口
     * @param string $type 构建器名称，'Form', 'Table' 或其他自定义构建器
     * @return zqs\admin\builder|form\Form|table\Table
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

        //js片段
        foreach ($this->vars['layui_modules_js'] as $k=>$v){
            if (file_exists($v['name'])){
                $this->vars['layui_modules_js'][$k]['content'] = $this->display($v['name'],$v['vars']);
            }else{
                $js_path = str2arr($tpl,'/')[0];
                $this->vars['layui_modules_js'][$k]['content'] = $this->display(__DIR__.'/'.$js_path."/js/{$v['name']}.js",$v['vars']);
            }

        }

        //dump($this->vars);
        $vars = array_merge($vars,$this->vars);
        return View::fetch($tpl,$vars);
        
    }
    
    
    
    
    
}