<?php
namespace zqs\admin\controller\cms;
use zqs\admin\controller\Admin;
use zqs\admin\model\Article as ArticleModel;
use zqs\admin\facade\Builder;
use zqs\admin\model\ArticleCate;
use zqs\admin\lib\Tree;

class Article extends Admin
{
    /**
     * 验证器
     */
    public $validate_class = '\zqs\admin\validate\Article';
    public $cate_list;
    public function initialize()
    {
        parent::initialize();
        $this->model = new ArticleModel();
        
        //所有分类
        $cate_list = ArticleCate::where('status',1)->select()->toArray();
        $cate_list = Tree::instance()->init($cate_list)->getTreeArray(0);
        $cate_list = Tree::instance()->getTreeList($cate_list);
        $cate = [];
        foreach ($cate_list as $k=>$v){
            $cate[$v['id']] = $v['name'];
        }
        $this->cate_list = $cate;
        //表单
        $this->form = Builder::make('form')
                                ->setPageTitle('文章')
                                ->setDataUrl('add')
                                ->addFormItems([
                                    ['input','文章标题','title'],
                                    ['select','分类','cate_id','',$cate],
                                    ['image','封面','cover'],
                                    ['editor','内容[:请编辑或粘贴]','content'],
                                    ['input','浏览量','read',0,'number','','','width:80px;']
                                ])
                                ->setVars('value_fields','id,title,cate_id,cover,read');
                                ;
    }
    
    /**
     * 首页-列表
     */
    public function index()
    {
        if ($this->request->isAjax()){
            list($where, $order, $page, $limit) = $this->getMap('title');
            $list = $this->model->where($where)->page($page)->order($order)->append(['cover_url','cate_text'])->paginate($limit);
            $this->success('succ','',$list);
        }
        return Builder::make('table')
                        ->setDataUrl('index')
                        ->setPageTitle('文章列表')
                        ->addColumns([
                            ['id','ID','checkbox'],
                            ['id','ID','','',['width'=>80]],
                            ['cover_url','封面','image'],
                            ['title','标题'],
                            ['cate_text','分类'],
                            ['read','浏览量'],
                            ['create_time','创建时间'],
                            ['update_time','更新时间']
                        ])
                        ->addTopBtn('add',['title'=>'发表文章','h'=>'95%'])
                        ->addTopBtn('delete')
                        ->addRightBtns('edit,delete')
                        ->addSearchItem('分类','cate_id','','select',$this->cate_list)
                        ->addSearchItem('[:标题]','keyword','text','input')
                        ->setHeight('full-168')
                        ->fetch();
        
    }
    
    
    
    
    
    
    
}