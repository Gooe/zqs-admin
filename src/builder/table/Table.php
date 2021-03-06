<?php
namespace zqs\admin\builder\table;

use zqs\admin\builder\Builder;
//use think\facade\View;

class Table extends Builder
{
    /**
     * 模板位置
     */
    private $tpl = '';
    /**
     * @var array 私有模板参数变量
     */
    protected $_vars = [
        'cols'              => [],//表格数据
        'search'            => [],//搜索表单
        'top_btns'          => [],//顶部按钮
        'page'              => [
            'open' => 'true',
            'limit' => 15,//每页条数
            'limits' => '[10,15,20,50,100,200]'//每页条数的选择项
        ],//分页相关
        'height'            => 'full-100',//高度
        'layui_modules'     => ['index','table'],//所使用的模块
        'toolbar_param'     => [],
    ];
    /**
     * 单元格类型
     */
    private $row_type = ['status','switch','icon','image','url'];
    /**
     * 右侧按钮
     */
    public $right_btns = [];
    
    /**
     * 初始化
     */
    public function __construct()
    {
        $this->tpl = 'table/table';
        $this->vars = array_merge($this->vars,$this->_vars);
    }

    /**
     * 设置分页选项
     * [
        'open' => 'true',
        'limit' => 15,//每页条数
        'limits' => '[10,15,20,50,100,200]'//每页条数的选择项
        ]
     * @param $page
     * @return $this
     */
    public function setPage($page)
    {
        $page = array_merge($this->vars['page'],$page);
        $this->setVars('page', $page);
        return $this;
    }

    /**
     * 设置表格高度
     * @param $h
     * @return $this
     */
    public function setHeight($h)
    {
        $this->setVars('height', $h);
        return $this;
    }



    /**
     * 添加一列
     * @param $field
     * @param $title
     * @param string $type 单元格类型
     * @param array $param 特殊的参数，例type=status时的参数 ['否','是:blue']
     * @param array $extra layui单元格其它参数
     * @return $this
     */
    public function addColumn($field,$title,$type='',$param=[],$extra=[])
    {
        $column = [
            'field' => $field, 
            'title' => $title,
        ];
        if (in_array($type, $this->row_type)){
            switch ($type){
                case 'status'://状态，不可编辑 0:灰,1:绿,2:赤,3:橙,4:蓝,5:黑
                    $json = json_encode($param);
                    $column['templet'] = <<<TPL
function(d){
    var list = {$json};
    var class_list = ['gray','green','red','orange','blue','black']
    var t,c;
    $(list).each(function(k,v){
        if(k==d.{$field}){
            t=v;
            c=class_list[k];
            arr=t.split(':');
            if(arr[1]){
                t = arr[0];
                c = arr[1];
            }
        }
    });
    return '<span class="layui-badge layui-bg-'+c+'">'+t+'</span>';                
}
TPL;
                    break;
                case 'icon'://图标
                    $column['templet'] = '<div><i class="layui-icon {{d.'.$field.'}}"></i></div>';
                    break;
                case 'image';//图片
                    $column['templet'] = 'function(d){return \'<img src=" \'+d.'.$field.'+ \'" onclick="layer.open({type:1,title:false,colseBtn:0,area: [\\\'auto\\\'],shadeClose:true,content:\\\'<img style=max-height:500px src=\\\'+this.src+\\\' >\\\'})"  style="max-height:100%;" >\'}';
                    break;
                case 'url';
                    $column['templet'] = '<div><a href="'.$param['url'].'" target="_blank" style="color:#01AAED;" >{{d.'.$field.'}}</a></div>';
                    break;
            }
        }else {
            $column['type'] = $type?:'normal';
        }
        
        $column = array_merge($column, $extra);
        $this->vars['cols'][] = $column;
        return $this;
    }
    /**
     * 添加多列
     */
    public function addColumns($columns=[])
    {
        if (!empty($columns)) {
            foreach ($columns as $column) {
                call_user_func_array([$this, 'addColumn'], $column);
            }
        }
        return $this;
    }
    

    /**
     * 添加toolbar 按钮
     * @param string $type delete|edit|other
     * @param array $attribute
     * @return $this
     */
    public function addRightBtn($type='',$attribute = [])
    {
        if ($type == '') {
            return $this;
        }
        $btn_attribute = [];
        switch ($type){
            case 'edit':
                $btn_attribute = [
                    'title' => '编辑',
                    'icon' => 'layui-icon-edit',
                    'event' => 'edit',
                    'class' => 'layui-btn-primary',
                    'url' => 'edit',
                    'h' => '80%',
                    'w' => '50%',
                ];
                break;
            case 'delete':
                $btn_attribute = [
                    'title' => '删除',
                    'icon' => 'layui-icon-delete',
                    'event' => 'ajax-get',
                    'class' => 'layui-btn-danger confirm delete',
                    'url' => 'delete',
                    'method' => 'get',
                ];
                break;
            default:
                $btn_attribute = [
                    'title' => '按钮',
                    'icon' => '',
                    'event' => 'ajax-get',
                    'class' => '',
                    'url' => 'javascript:;',
                    'method' => 'get',
                ];
                break;
        }
        // 合并自定义属性
        if ($attribute && is_array($attribute)) {
            $btn_attribute = array_merge($btn_attribute, $attribute);
        }
        if ($btn_attribute){
            $this->right_btns[] = $btn_attribute;
        }
        
        
        return $this;
    }
    /**
     * 一次性添加多个右侧按钮
     * @param array|string $buttons 按钮类型
     * 例如：
     * $builder->addRightButtons('edit');
     * $builder->addRightButtons('edit,delete');
     * $builder->addRightButtons(['edit', 'delete']);
     * $builder->addRightButtons(['edit' => ['title' => 'xx'], 'delete']);
     * @return $this
     */
    public function addRightBtns($buttons = [])
    {
        if (!empty($buttons)) {
            $buttons = is_array($buttons) ? $buttons : explode(',', $buttons);
            foreach ($buttons as $key => $value) {
                if (is_numeric($key)) {
                    $this->addRightBtn($value);
                } else {
                    $this->addRightBtn($key, $value);
                }
            }
        }
        return $this;
    }
    
    /**
     * 解析toolbar
     * @todo 宽度问题
     */
    public function toolParse()
    {
        if (!empty($this->right_btns)){
            $html = '<div><div class="">';
            //按钮组
            if (count($this->right_btns)>1){
               // $html = '<div><div class="layui-btn-group">';
            }
            foreach ($this->right_btns as $btn){
                $url = $btn['url']??'';
                $method = $btn['method']??'get';
                $h = $btn['h']??'80%';
                $w = $btn['w']??'50%';
                //<i class=\"layui-icon {$btn['icon']} \"></i>
                $html .= "<a class=\"layui-btn layui-btn-xs {$btn['class']}\" lay-event=\"{$btn['event']}\" data-url=\"{$url}\" data-method=\"{$method}\" data-h=\"{$h}\" data-w=\"{$w}\" >{$btn['title']}</a>";
            }
            $html .= '</div></div>';
            $extra_param = ['toolbar'=>$html,'fixed'=>'right','align'=>'center'];
            
            $extra_param = array_merge($extra_param,$this->vars['toolbar_param']);
            $this->addColumn('', '操作','','',$extra_param);
        }
    }
    
    /**
     * 添加搜索
     * @param string $title 标题
     * @param string $field 搜索的字段
     * @param string $type input类型时有效为 <input type="$type" />
     * @param string $item  input/select..
     * @param array $extra $item=select时的选择项
     * @return \zqs\admin\builder\table\Table
     */
    public function addSearchItem($title='',$field='',$type='text',$item='input',$extra=[])
    {
        if (preg_match('/(.*)\[:(.*)\]/', $title, $matches)) {
            $title       = $matches[1];
            $placeholder = $matches[2];
        }
        $item = [
            'name' => $title,
            'field' => $field,
            'type' => $type,
            'item' => $item,
            'placeholder' => isset($placeholder) ? $placeholder : '请输入',
            'extra' => $extra,
        ];
        $this->vars['search'][] = $item;
        return $this;
    }
    /**
     * 批量添加搜索
     */
    public function addSearchItems($searchs)
    {
        if (!empty($searchs)) {
            foreach ($searchs as $search) {
                call_user_func_array([$this, 'addSearchItem'], $search);
            }
        }
        return $this;
    }

    /**
     * 添加头部按钮
     * @param string $type 内置按钮 add/delete
     * @param array $attribute 自定义按钮
     * @return $this
     */
    public function addTopBtn($type='',$attribute = [])
    {
        if ($type == '') {
            return $this;
        }
        $btn_attribute = [];
        switch ($type){
            case 'add':
                $btn_attribute = [
                    'title' => '添加',
                    'icon' => 'layui-icon-edit',
                    'event' => 'edit',
                    'class' => 'layuiadmin-btn-admin layui-btn iframe',
                    'url' => 'add'
                ];
                break;
            case 'delete':
                $btn_attribute = [
                    'title' => '删除',
                    'icon' => 'layui-icon-delete',
                    'class' => 'layui-btn-danger confirm ajax-get',
                    'url' => 'delete',
                    'method' => 'post',
                ];
                break;
            default:
                $btn_attribute = [
                    'title' => '按钮',
                    'icon' => '',
                    'event' => '',
                    'class' => '',
                    'url' => 'javascript:;',
                    'method' => '',
                ];
                break;
        }
        // 合并自定义属性
        if ($attribute && is_array($attribute)) {
            $btn_attribute = array_merge($btn_attribute, $attribute);
        }
        if ($btn_attribute){
            $this->vars['top_btns'][] = $btn_attribute;
        }
        
        
        return $this;
    }
    /**
     * 一次性添加多个右侧按钮
     * @param array|string $buttons 按钮类型
     * 例如：
     * $builder->addRightButtons('edit');
     * $builder->addRightButtons('edit,delete');
     * $builder->addRightButtons(['edit', 'delete']);
     * $builder->addRightButtons(['edit' => ['title' => 'xx'], 'delete']);
     * @return $this
     */
    public function addTopBtns($buttons = [])
    {
        if (!empty($buttons)) {
            $buttons = is_array($buttons) ? $buttons : explode(',', $buttons);
            foreach ($buttons as $key => $value) {
                if (is_numeric($key)) {
                    $this->addTopBtn($value);
                } else {
                    $this->addTopBtn($key, $value);
                }
            }
        }
        return $this;
    }

    
    /**
     * end
     */
    public function end()
    {
        //解析工具栏
        $this->toolParse();
    }
    
    /**
     * 重写模板输出
     */
    public function fetch($tpl='',$vars=[])
    {
        if (empty($this->vars['data_url'])){
            throw new \think\Exception('未设置数据url', 8003);
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