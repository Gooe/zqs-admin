<?php
namespace zqs\admin\controller\cms;
use zqs\admin\controller\Admin;
use zqs\admin\model\ArticleCate;
use zqs\admin\facade\Builder;
use zqs\admin\lib\Tree;
use zqs\admin\model\Article;

class Cate extends Admin{
    
    public function initialize()
    {
        parent::initialize();
        $this->model = new ArticleCate();
        //表单
        //顶级分类
        $cate_top = [0 => '顶级分类'];
        $cate_db = $this->model->where('status',1)->where('pid',0)->order('sort desc')->column('name','id');
        if ($cate_db){
            $cate_top = array_merge($cate_top,$cate_db);
        }
        
        $this->form = Builder::make('form')
                                ->setDataUrl('add')
                                ->addFormItems([
                                    ['input','分类名称','name'],
                                    ['select','所属分类','pid','',$cate_top],
                                    ['image','封面','cover'],
                                    ['input','排序','sort',0,'number','','数字越大越靠前','width:80px;']
                                ])
                                ->setPageTitle('分类');
    }
    
    /**
     * 首页-列表
     */
    public function index()
    {
        if ($this->request->isAjax()){
            //取出所有父类
            $cate_list = $this->model->order('sort desc')->where('status',1)->append(['cover_url'])->select()->toArray();
            $cate_list = Tree::instance()->init($cate_list)->getTreeArray(0);
            $cate_list = Tree::instance()->getTreeList($cate_list);
            $data = [
                'data' => $cate_list
            ];
            $this->success('succ','',$data);
        }
        return Builder::make('table')
                        ->setDataUrl('index')
                        ->setPageTitle('分类')
                        ->addColumns([
                            ['id','ID','checkbox'],
                            ['id','ID','','',['width'=>80]],
                            ['cover_url','封面','image'],
                            ['name','分类名称'],
                            ['sort','排序'],
                            ['create_time','创建时间'],
                            ['update_time','更新时间']
                        ])
                        ->addTopBtn('add',['title'=>'添加分类'])
                        ->addRightBtns('edit,delete')
                        ->setPage(['open'=>'false'])
                        ->fetch();
        
    }
    
    /**
     * 删除分类
     */
    public function delete($ids='')
    {
        if (empty($ids)) $this->error('请选择你要操作的数据');
        //是否有子类
        $cate = $this->model;
        if ($cate->where('pid',$ids)->find()){
            return $this->error('分类下有子分类，不能进行删除操作');
        }
        //是否有文章
        if (Article::where('cate_id',$ids)->where('status',1)->find()){
            return $this->error('分类下有文章，不能删除');
        }
        parent::delete();
    }
    
    
    
    
    
    
    
    
    
    
    
}