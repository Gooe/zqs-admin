<?php
namespace zqs\admin\controller\auth;
use zqs\admin\controller\Admin;
use zqs\admin\model\AuthGroup;
use zqs\admin\facade\Builder;
use zqs\admin\lib\Tree;
use zqs\admin\model\AuthRule;

class Group extends Admin
{
    //当前登录管理员所有子节点组别
    protected $childrenIds = [];
    //当前组别列表数据
    protected $groupdata = [];
    public function initialize()
    {
        parent::initialize();
        $this->model = new AuthGroup();
        //获取用户组
        $groups = $this->auth->getGroups();
        //取出所有分组
        $grouplist = $this->model->where('status',1)->select();
        $objlist = [];
        foreach ($groups as $K => $v)
        {
            // 取出包含自己的所有子节点
            $childrenlist = Tree::instance()->init($grouplist)->getChildren($v['id'], true);
            $obj = Tree::instance()->init($childrenlist)->getTreeArray($v['pid']);
            $objlist = array_merge($objlist, Tree::instance()->getTreeList($obj));
        }
        //下拉菜单
        $groupdata = [];
        foreach ($objlist as $k => $v){
            $groupdata[$v['id']] = $v['name'];
        }
        $this->groupdata = $groupdata;
        //所有子id
        $this->childrenIds = array_keys($groupdata);
        $this->assign('groupdata', $groupdata);
    }
    /**
     * 角色组列表
     */
    public function index()
    {
        if ($this->request->isAjax()){
            list($where, $order, $page, $limit) = $this->getMap();
            $list = $this->model->where($where)->page($page)->order($order)->paginate($limit);
            
            $this->success('succ','',$list);
        }
        return Builder::make('table')
                        ->setPageTitle('菜单管理')
                        ->setDataUrl('index')
                        ->setPage(['open'=>'true'])
                        ->addColumns([
                            ['id','ID','checkbox'],
                            ['pid','PID'],
                            ['name','名称'],
                            ['create_time','创建时间'],
                            ['update_time','更新时间']
                        ])
                        ->addTopBtn('add',['title'=>'添加角色组'])
                        ->addRightBtns('edit,delete')
                        ->fetch();
        
    }
    
    /**
     * 添加
     */
    public function add($data='')
    {
        if ($this->request->isPost())
        {
            $params = $this->request->post("row/a");
            if (!in_array($params['pid'], $this->childrenIds))
            {
                $this->error('超出您的权限范围');
            }
            if (empty($params['name']))
            {
                $this->error('请输入角色组名称');
            }
            $parentmodel = $this->model->find($params['pid']);
            if (!$parentmodel)
            {
                $this->error('爸爸去哪儿了？');
            }
            // 父级别的规则节点
            $parentrules = explode(',', $parentmodel->rules);
            // 当前组别的规则节点
            $currentrules = $this->auth->getRuleIds();
            $rules = $params['rule_ids'];
            // 如果父组不是超级管理员则需要过滤规则节点,不能超过父组别的权限
            $rules = in_array('*', $parentrules) ? $rules : array_intersect($parentrules, $rules);
            // 如果当前组别不是超级管理员则需要过滤规则节点,不能超当前组别的权限
            $rules = in_array('*', $currentrules) ? $rules : array_intersect($currentrules, $rules);
            $params['rules'] = implode(',', $rules);
            if ($params)
            {
                $this->model->save($params);
                $this->success('添加成功');
            }else {
                $this->error('添加失败');
            }
        }
        
        return $this->fetch('auth/group_add');
    }
    /**
     * 编辑
     */
    public function edit($id,$data=null)
    {
        $row = $this->model->find($id);
        if ($this->request->isPost()){
            if (!$row){
                return $this->error('找不到组织~');
            }
            if ($row['pid']==0){
                return $this->error('禁止操作');
            }
            
            $params = $this->request->post("row/a");
            // 父节点不能是它自身的子节点TODO::这里好像错了，这里的childrenIds是当前管理员的并不是自身的
            if (!in_array($params['pid'], $this->childrenIds)){
                $this->error('超出了你的权限范围');
            }
            if (in_array($params['pid'], Tree::instance()->init($this->model->where('status',1)->select())->getChildrenIds($id, true))){
                $this->error('你是爸爸，不要当孙子呀~');
            }
            
            $parentmodel = $this->model->find($params['pid']);
            if (!$parentmodel){
                $this->error('找不到爸爸~');
            }
            // 父级别的规则节点
            $parentrules = explode(',', $parentmodel->rules);
            // 当前组别的规则节点
            $currentrules = $this->auth->getRuleIds();
            $rules = $params['rule_ids'];
            // 如果父组不是超级管理员则需要过滤规则节点,不能超过父组别的权限
            $rules = in_array('*', $parentrules) ? $rules : array_intersect($parentrules, $rules);
            // 如果当前组别不是超级管理员则需要过滤规则节点,不能超当前组别的权限
            $rules = in_array('*', $currentrules) ? $rules : array_intersect($currentrules, $rules);
            $params['rules'] = implode(',', $rules);
            if ($params){
                $row->save($params);
                return $this->success('编辑成功');
            }else {
                return $this->error('编辑失败');
            }
        }
        if ($row['pid']==0){
            return '禁止操作';
        }
        $this->assign('edit',1);
        $this->assign("row", $row);
        return $this->fetch('auth/group_add');
    }
    
    /**
     * 读取角色权限树
     */
    public function roletree()
    {
        $model = $this->model;
        $id = $this->request->post("id");
        $pid = $this->request->post("pid");
        $parentgroupmodel = $model->find($pid);
        if (!$parentgroupmodel){
            $this->error('pid,error~');
        }
        $currentgroupmodel = NULL;
        if ($id){
            $currentgroupmodel = $model->find($id);
        }
        if (($pid || $parentgroupmodel) && (!$id || $currentgroupmodel)){
            $id = $id ? $id : NULL;
            //读取父类角色所有节点列表
            $parentrulelist = AuthRule::where(in_array('*', explode(',', $parentgroupmodel->rules)) ? ['status'=>1] : [['status','=',1],['id','in',$parentgroupmodel->rules]] )->select();
            
            //读取当前角色下规则ID集合
            $admin_rule_ids = $this->auth->getRuleIds();
            $superadmin = $this->auth->isSuperAdmin();
            $current_rule_ids = $id ? explode(',', $currentgroupmodel->rules) : [];
            
            if (!$id || !in_array($pid, Tree::instance()->init($model->where('status',1)->select())->getChildrenIds($id, true))){
                //构造jstree所需的数据
                $nodelist = [];
                foreach ($parentrulelist as $k => $v){
                    if (!$superadmin && !in_array($v['id'], $admin_rule_ids))
                        continue;
                    $state = in_array($v['id'], $current_rule_ids);
                    $nodelist[] = [
                        'id' => $v['id'], 
                        'pid' => $v['pid'] ? $v['pid'] : 0, 
                        'name' => $v['title'],
                        'checked' => $state,
                    ];
                        
                }
                return $this->success('获取成功','',$nodelist);
            }else{
                return $this->error('你是爸爸，不能当孙子~');
            }
        }else{
            return $this->error('找不到组织~');
        }
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}