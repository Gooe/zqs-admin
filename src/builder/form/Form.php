<?php
namespace zqs\admin\builder\form;
use zqs\admin\builder\Builder;

class Form extends Builder
{
    /**
     * 模板位置
     */
    private $tpl = '';
    /**
     * @var array 模板参数变量
     */
    protected $vars = [
        'data_url'          => '',//数据url
        'page_title'        => '',//页面标题
        'page_tips'         => '',//页面提示
        'form_title'        => '',//表单标题
        'post_url'          => '',//表单提交地址
        'form_items'        => [],//表单项目
        'layui_modules'     => ['index','form'],//所使用的模块
        'layui_modules_js'  => [],//额外的js代码片段
        'js_list'           => [],// js文件列表
        'css_list'          => [],// css列表
        'extra_js'          => '',// 额外JS代码
        'extra_css'         => '',// 额外CSS代码
    ];
    
    /**
     * 初始化
     */
    public function __construct()
    {
        $this->tpl = 'form/form';
    }
    
    /**
     * 设置数据url
     */
    public function setDataUrl($url)
    {
        $this->vars['data_url'] = $url;
        return $this;
    }
    
    /**
     * 设置表单头部标题
     * @param string $title 标题
     * @return $this
     */
    public function setFormTitle($title = '')
    {
        $this->setVars('form_title', trim($title));
        return $this;
    }
    
    /**
     * 添加layui module
     * @param string $name
     * @return \zqs\admin\builder\form\Form
     */
    public function addLayuiModules($name)
    {
        $this->vars['layui_modules'][] = $name;
        $this->vars['layui_modules'] = array_unique($this->vars['layui_modules']);
        return $this;
    }
    
    public function addLayuiModulesJs($name)
    {
        $this->vars['layui_modules_js'][] = $name;
        $this->vars['layui_modules_js'] = array_unique($this->vars['layui_modules_js']);
        return $this;
    }
    
    /**
     * 添加input表单
     * @param string $title
     * @param string $name
     * @param string $type [text/number/email/tel...] checkbox/radio不用使用
     * @param string $value
     * @param string $verify 验证规则required/phome/mobile/url/number/date/identity身份证
     * @param string $help 帮助文本
     * @param string $extra_attr 额外属性 such as: disable=""
     */
    public function addInput($title='',$name='',$type='text',$value='',$verify='required',$help='',$extra_css='',$extra_attr='')
    {
        if (preg_match('/(.*)\[:(.*)\]/', $title, $matches)) {
            $title       = $matches[1];
            $placeholder = $matches[2];
        }
        $item = [
            'item'        => 'input',
            'title'       => $title,
            'name'        => $name,
            'type'        => $type,
            'value'       => $value,
            'verify'      => $verify,
            'help'        => $help,
            'extra_attr'  => $extra_attr,
            'extra_css'   => $extra_css,
            'placeholder' => isset($placeholder) ? $placeholder : '请输入'.$title,
        ];
        $this->vars['form_items'][] = $item;
        return $this;
    }
    
    /**
     * 添加普通下拉菜单
     * @param string $name 下拉菜单名
     * @param string $title 标题
     * @param string $tips 提示
     * @param array  $options 选项
     * @param string $value 默认值
     * @param string $verify 验证规则required/phome/mobile/url/number/date/identity身份证
     * @param string $help 帮助文本
     * @param string $extra_attr 额外属性
     * @param string $extra_css 额外css
     * @return mixed
     */
    public function addSelect($title='',$name='',$options=[],$value='',$verify='required',$help='',$extra_css='',$extra_attr='')
    {
        if (preg_match('/(.*)\[:(.*)\]/', $title, $matches)) {
            $title       = $matches[1];
            $placeholder = $matches[2];
        }
        $type = 'select';
        
        if ($extra_attr != '') {
            if (in_array('multiple', explode(' ', $extra_attr))) {
                $type = 'select2';
                $this->js('xm-select','admin');
            }
        }
        
        $item = [
            'item'        => $type,
            'name'        => $name,
            'title'       => $title,
            'options'     => $options,
            'value'       => $value,
            'verify'      => $verify,
            'help'        => $help,
            'extra_attr'  => $extra_attr,
            'extra_css'   => $extra_css,
            'placeholder' => isset($placeholder) ? $placeholder : '请选择',
        ];
        
        $this->vars['form_items'][] = $item;
        return $this;
    }
    /**
     * 添加开关
     * @param string $title
     * @param string $name
     * @param string $value
     * @param string $text
     * @param string $checked
     * @return \zqs\admin\builder\form\Form
     */
    public function addSwitch($title='',$name='',$value='',$text='开|关',$checked='')
    {
        $item = [
            'item'        => 'switch',
            'name'        => $name,
            'title'       => $title,
            'value'       => $value,
            'text'        => $text,
            'checked'     => $checked
        ];
        
        $this->vars['form_items'][] = $item;
        return $this;
    }
    
    /**
     * 图标选择控件
     * @param string $title
     * @param string $name
     * @param string $value
     * @return \zqs\admin\builder\form\Form
     */
    public function addIcon($title='',$name='',$value='')
    {
        $item = [
            'item'        => 'icon',
            'name'        => $name,
            'title'       => $title,
            'value'       => $value,
        ];
        $this->addLayuiModules('iconPicker');
        $this->addLayuiModulesJs('iconPicker');
        $this->vars['form_items'][] = $item;
        return $this;
    }
    
    /**
     * 添加表单项
     * 这个是addCheckbox等方法的别名方法，第一个参数传表单项类型，其余参数与各自方法中的参数一致
     * @param string $type 表单项类型
     * @param string $name 表单项名
     * @return $this
     */
    public function addFormItem($type = '', $name = '')
    {
        if ($type != '') {
            // 获取所有参数值
            $args = func_get_args();
            array_shift($args);
            
            $method = 'add'. ucfirst($type);
            call_user_func_array([$this, $method], $args);
        }
        return $this;
    }
    
    /**
     * 一次性添加多个表单项
     * @param array $items 表单项
     * @return $this
     */
    public function addFormItems($items = [])
    {
        if (!empty($items)) {
            foreach ($items as $item) {
                call_user_func_array([$this, 'addFormItem'], $item);
            }
        }
        return $this;
    }
    
    
    
    /**
     * end
     */
    public function end()
    {
        
        foreach ($this->vars['layui_modules_js'] as $k=>$v){
            $this->vars['layui_modules_js'][$k] = $this->display(__DIR__."/js/{$v}.js");
        }
        
    }
    
    /**
     * 重写模板输出
     */
    public function fetch($tpl='',$vars=[])
    {
        if (empty($this->vars['data_url'])){
            throw new \think\Exception('未设置请求url', 8004);
        }
        if ($tpl != '') {
            $this->tpl = $tpl;
        }
        if (!empty($vars)) {
            $this->vars = array_merge($this->vars, $vars);
        }
        return parent::fetch($this->tpl,$this->vars);
        
    }
    
}