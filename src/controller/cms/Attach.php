<?php
namespace zqs\admin\controller\cms;
use zqs\admin\controller\Admin;
use zqs\admin\facade\Builder;
use zqs\admin\model\Attachment;

class Attach extends Admin
{
    public function initialize()
    {
        parent::initialize();
        $this->model = new Attachment();
    }
    
    /**
     * 列表
     */
    public function index()
    {
        if ($this->request->isAjax()){
            list($where, $order, $page, $limit) = $this->getMap();
            $list = $this->model->where($where)->page($page)->order($order)->paginate($limit);
            
            $this->success('succ','',$list);
        }
        return Builder::make('table')
                        ->setPageTitle('附件列表')
                        ->setDataUrl('index')
                        ->addColumns([
                            ['id','ID','checkbox'],
                            ['id','ID'],
                            ['url','预览','image'],
                            ['name','名称'],
                            ['url','url'],
                            ['filesize','大小'],
                            ['storage','仓库'],
                            ['create_time','创建时间'],
                            ['update_time','更新时间']
                        ])
                        ->addRightBtn('delete')
                        ->addTopBtn('delete')
                        ->setHeight('full-100')
                        ->fetch();
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}