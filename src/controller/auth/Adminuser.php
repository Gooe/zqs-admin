<?php
namespace zqs\admin\controller\auth;
use zqs\admin\controller\Admin;
use zqs\admin\model\Admin as AdminModel;
use zqs\admin\facade\Builder;
use zqs\admin\lib\Tree;
use zqs\admin\model\AuthGroup;

class Adminuser extends Admin
{
    /**
     * 验证器
     */
    public $validate_class = '\zqs\admin\validate\Admin';
    /**
     * 当前登录管理员所有子节点组别
     * @var array
     */
    protected $childrenIds = [];
    /**
     * 当前组别列表数据
     * @var array
     */
    protected $groupdata = [];
    /**
     * 构建的表单
     */
    public $form;
    public function initialize()
    {
        parent::initialize();
        $this->model = new AdminModel();
        //获取用户组
        $groups = $this->auth->getGroups();
        //取出所有分组
        $grouplist = AuthGroup::where('status',1)->select();
        $objlist = [];
        foreach ($groups as $K => $v){
            // 取出包含自己的所有子节点
            $tree = Tree::instance();
            $childrenlist = $tree->init($grouplist)->getChildren($v['id'], true);
            $obj = $tree->init($childrenlist,'pid',' ')->getTreeArray($v['pid']);
            $objlist = array_merge($objlist, $tree->getTreeList($obj));
        }
        
        //下拉菜单
        $groupdata = [];
        foreach ($objlist as $k => $v){
            $groupdata[] = [
                'name' => $v['name'],
                'value' => $v['id']
            ];
        }
        $this->groupdata = $groupdata;
        //所有子id
        $this->childrenIds = array_column($groupdata,'value');
        
        //初始化表单
        $this->form();
        
    }
    
    /**
     * 首页列表
     */
    public function index()
    {
        if ($this->request->isAjax()){
            list($where, $order, $page, $limit) = $this->getMap();
            $list = $this->model->where($where)->page($page)->order($order)->paginate($limit)->each(function($item, $key){
                $item->id = $item->uid;
            });
            
            $this->success('succ','',$list);
        }
        return Builder::make('table')
                        ->setDataUrl('index')
                        ->setPageTitle('管理员')
                        ->addColumns([
                            ['id','ID','checkbox'],
                            ['username','用户名'],
                            ['nickname','昵称'],
                            ['headimg','头像'],
                            ['email','邮箱'],
                            ['login_time','最后登录时间'],
                            ['create_time','创建时间'],
                            ['update_time','更新时间']
                        ])
                        ->setPage(['open'=>'true'])
                        ->addTopBtn('add',['title'=>'添加管理员'])
                        ->addRightBtns('edit,delete')
                        ->fetch();
    }
    
    /**
     * 表单
     */
    public function form()
    {
        $this->form =  Builder::make('form')
                    ->setDataUrl('add')
                    ->setPageTitle('管理员')
                    ->addFormItems([
                        ['select','角色组','group','',$this->groupdata,'required','','','multiple'],
                        ['input','用户名','username'],
                        ['input','昵称','nickname'],
                        ['input','邮箱','email'],
                        ['input','密码','password'],
                    ]);
    }
    /**
     * 插入属性
     * @param array $data
     * @return parent::add
     */
    public function add($data=null)
    {
        $data = input();
        //注入参数，过滤不允许的组别,避免越权
        $data['childrenIds'] = $this->childrenIds;
        return parent::add($data);
    }
    
    /**
     * 编辑
     */
    public function edit($id,$data=null)
    {
        if ($id==1){
            return $this->result('',0,'禁止编辑','json');
        }
        $data = input();
        //过滤不允许的组别,避免越权，注入参数
        $data['childrenIds'] = $this->childrenIds;
        //选中
        $grouplist = $this->auth->getGroups($id);
        $check_ids = array_column($grouplist, 'group_id');
        /* foreach ($this->groupdata as $k=>$v){
            if (in_array($v['value'], $check_ids)){
                $this->groupdata[$k]['selected'] = true;
            }
        } */
        //重置表单
        $this->form =  Builder::make('form')
                        ->setDataUrl('edit')
                        ->setPageTitle('管理员')
                        ->addFormItems([
                            ['select','角色组','group',json_encode($check_ids),$this->groupdata,'required','','','multiple'],
                            ['input','用户名','username','','text','req','不可更改','','disabled'],
                            ['input','昵称','nickname'],
                            ['input','邮箱','email'],
                            ['input','密码','password','','text','','不填不更改'],
                        ])
                        ->setVars('value_fields','nickname,username,email');
        return parent::edit($id,$data);
    }
    
    
    
    
    
    
    
    
    
    
    
    
}