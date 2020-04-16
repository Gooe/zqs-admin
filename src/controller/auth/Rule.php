<?php
namespace zqs\admin\controller\auth;

use zqs\admin\facade\Builder;
use zqs\admin\model\AuthRule;
use think\Request;
use zqs\admin\lib\Tree;
use think\facade\Cache;
use zqs\admin\controller\Admin;
class Rule extends Admin
{
    /**
     * 菜单列表
     * @var array
     */
    protected $rule_list = [];
    /**
     * options
     */
    protected $rule_data;
    
    public function initialize()
    {
        parent::initialize();
        $this->model = new AuthRule();
        // 必须将结果集转换为数组
        $ruleList = $this->model->order('weigh', 'desc')->select()->toArray();
        
        Tree::instance()->init($ruleList);
        $this->rule_list = Tree::instance()->getTreeList(Tree::instance()->getTreeArray(0), 'title');
        
        //下拉
        $this->rule_data = [0 => '根菜单'];
        foreach ($this->rule_list as $k => &$v)
        {
            if ($v['ismenu'])
                $this->rule_data[$v['id']] = $v['title'];
        }
        $this->form();
        //dump($ruledata);
        //$this->view->assign('ruledata', $ruledata);
    }
    /**
     * 首页
     */
    public function index()
    {
        if ($this->request->isAjax()){
            $data = [
                'data' => $this->rule_list
            ];
            $this->success('succ','',$data);
        }
        return Builder::make('table')
                ->setDataUrl('index')
                ->setPageTitle('菜单管理')
                ->setPageTips('警告：非技术人员请勿操作！')
                //->addColumn('id','ID','checkbox')
                //->addColumn('title','名称')
                ->addColumns([
                    ['id','ID','checkbox'],
                    ['title','名称'],
                    ['icon','图标','icon','',['width'=>80]],
                    ['name','URL'],
                    ['ismenu','菜单显示','status',['否','是'],['width'=>100]],
                    ['create_time','创建时间'],
                    ['update_time','更新时间']
                ])
                //->addRightBtn('edit')
                //->addRightBtn('del')
                ->addRightBtns([
                    'edit',
                    'delete' => ['class'=>'reload confirm layui-btn-danger'],
                ])
                //->addSearchItem('名称','title')
                //->addSearchItem('url','name')
                /* ->addSearchItems([
                    ['名称','title','text','input'],
                    ['url','name','text'],
                    ['菜单[:请选择菜单]','ismenu','','select',[''=>'不限',0=>'否',1=>'是']]
                ]) */
                //->addSearchItem('','keyword','text','input','菜单名')
                //->addTopBtn('delete')
                ->addTopBtns([
                    'add',
                    //'delete',// => ['url'=>'menu/more']
                ])
                ->setHeight('')
                ->setPage(['open'=>'false'])
                ->fetch();
    }
    
    /**
     * 删除
     */
    public function delete()
    {
        $ids = input('ids/d');
        if ($ids)
        {
            if ($ids<=38){
                $this->error('禁止操作');
            }
            $del_ids = Tree::instance()->getChildrenIds($ids, true);
            $del_ids = array_unique($del_ids);
            
            $count = $this->model->where('id', 'in', $del_ids)->delete();
            if ($count)
            {
                Cache::delete('__menu__');
                $this->success('删除成功');
            }else {
                $this->error('删除失败');
            }
        }
        $this->error('请选择数据');
    }
    
    /**
     * 表单
     */
    public function form()
    {
        $this->form =  Builder::make('form')
                ->setDataUrl('add')
                ->setPageTitle('菜单')
                //->setPageTips('just a example.')
                //->setFormTitle('测试表单')
                //->addInput('菜单名称[:请输入菜单名称]','title','text','','required','有可能会显到面板左侧','width:200px;')
                //->addInput('路径','name')
                //->addFormItem('input','哈哈')
                ->addFormItems([
                    ['input','菜单名称[:请输入菜单名称]','title','','text','required','有可能会显到菜单面板','width:200px;'],
                    ['icon','图标','icon'],
                    ['input','路径','name','','text',''],
                    ['select','父级菜单','pid','',$this->rule_data,'required'],
                    ['switch','是否菜单','ismenu','0','是|否'],
                    ['input','条件','condition','','text',''],//可为空
                    ['input','备注','remark','','text',''],
                    ['input','权重','weigh','0','number','','权重越高越靠前','width:60px;'],
                ]);
                //->fetch();
    }
    
    
    
    
    
    
    
}