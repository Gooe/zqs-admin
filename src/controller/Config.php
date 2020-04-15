<?php
namespace zqs\admin\controller;
use zqs\admin\facade\Builder;
use zqs\admin\model\Config as ConfigModel;

class Config extends Admin
{
    /**
     * 验证器
     */
    public $validate_class = '\zqs\admin\validate\Config';
    public function initialize()
    {
        parent::initialize();
        $this->model = new ConfigModel();
        //分组数据
        $groups = config_db('config_group');
        //类型
        $types = config_db('form_item_type');
        //表单
        $this->form = Builder::make('form')
                                ->setPageTitle('配置')
                                ->addFormItems([
                                    ['radio','分组','group','base',$groups],
                                    ['select','类型','type','',$types],
                                    ['input','变量名','name','','text','required','<span style="color:#FF5722">例： web_site_title，调用方法：config_db(\'web_site_title\')</span>'],
                                    ['input','标题','title','','text','required','一般由中文组成，仅用于显示'],
                                    ['textarea','配置值[:该配置的具体内容]','value','','required'],
                                    ['textarea','配置项[:用于单选、多选、下拉、联动等类型，每行一个]','options','',''],
                                    ['input','备注','remark','','text',''],
                                    ['input','排序','sort','0','number','required','越小越靠前'],
                                ])
                                ->setDataUrl('add');
    }
    /**
     * 配置管理
     */
    public function index($group='base')
    {
        if ($this->request->isAjax()){
            list($where, $order, $page, $limit) = $this->getMap();
            $where[] = ['group','=',$group];
            $order = 'sort asc,id asc';
            $list = $this->model->where($where)->page($page)->order($order)->paginate($limit);
                
            $this->success('succ','',$list);
        }
        //dump(config_db());
        // 配置分组信息
        $list_group = config_db('config_group');
        $tab_list = [];
        foreach ($list_group as $key => $value) {
            $tab_list[$key]['title'] = $value;
            $tab_list[$key]['url']   = url('config/index', ['group' => $key]);
        }
        
        return Builder::make('table')
                        ->setDataUrl(url('config/index',['group'=>$group]))
                        ->setPageTitle('配置管理')
                        ->addColumns([
                            ['id','ID','checkbox'],
                            ['name','变量名'],
                            ['title','标题'],
                            ['type','类型'],
                            ['sort','排序'],
                            ['create_time','创建时间'],
                            ['update_time','更新时间']
                        ])
                        ->addTopBtns('add,delete')
                        ->addRightBtns('edit,delete')
                        ->addTabNav($tab_list, $group)
                        ->setHeight('full-160')
                        ->fetch();
    }
    
    
    /**
     * 系统配置,即配置设置
     */
    public function system($group='base')
    {
        // 配置分组信息
        $list_group = config_db('config_group');
        $tab_list = [];
        foreach ($list_group as $key => $value) {
            $tab_list[$key]['title'] = $value;
            $tab_list[$key]['url']   = url('config/system', ['group' => $key]);
        }
        //表单数据
        $data_list = $this->model->where('status',1)->where('group',$group)->field('id,name,title,value,options,type,remark')->order('sort asc,id asc')->select()->toArray();
        //dump($data_list);
        $forms = [];
        foreach ($data_list as $k=>$v){
            if ($v['type']=='array'){
                $v['type'] = 'textarea';
            }
            $items = [$v['type'],$v['title'],$v['name'],$v['value']];
            //单选
            if (in_array($v['type'], ['select','radio'])){
                $items[] = parse_attr($v['options']);
            }
            //多选
            if ($v['type']=='select2'){
                $items[0] = 'select';
                $items[3] = json_encode(str2arr($v['value']));
                $opt = parse_attr($v['options']);
                $options = [];
                foreach ($opt as $k=>$v){
                    $options[] = ['name'=>$v,'value'=>$k];
                }
                array_push($items,$options, 'required','','','multiple');
                
            }
            
            $forms[] = $items;
        }
        //dump($forms);
        //$info = ['c'=>1];$this->assign('info',$info);
        return Builder::make('form')
                        ->setPageTitle('系统配置')
                        ->setDataUrl('system_save')
                        ->addTabNav($tab_list, $group)
                        ->addFormItems($forms)
                        ->fetch();
    }
    
    /**
     * 系统配置保存，分开操作，便于权限管理
     */
    public function system_save()
    {
        if ($this->request->isAjax()){
            $config = array_unique(array_filter(input('post.'), function ($val) {
                if ($val === '0' || $val === 0 || $val != false) {
                    return true;
                } else {
                    false;
                }
            }));
            foreach ($config as $name => $value)
            {
                //如果是数组则json_encode存入
                //if (is_array($value)) $value = json_encode($value);
                // 如果值是数组则转换成字符串，适用于复选框等类型
                if (is_array($value)) $value = arr2str($value);
                $this->model->where('name',$name)->update(['value'=>$value,'update_time'=>time()]);
            }
            //清除缓存
            cache('__db_config__',null);
            $this->success('保存成功');
        }
    }
    
    
    
    
    
    
    
    
    
    
    
}