<?php
namespace zqs\admin\traits;

use think\facade\Request;

trait Curd
{
    
    
    /**
     * 获取过滤条件
     */
    public function getMap($searchField='')
    {
        $where = [];
        
        $filter = Request::param('filter');
        
        if (isset($filter['keyword'])) {
            $where[] = [$searchField, 'like', '%'.$filter['keyword'].'%'];
            unset($filter['keyword']);
        }
        $keyword = Request::param('keyword');
        if ($keyword) {
            $where[] = [$searchField, 'like', '%'.$keyword.'%'];
        }
        if ($filter) {
            foreach ($filter as $key => $value) {
                if ($value !== '') {
                    $where[] = [$key, '=', $value];
                }
            }
        }
        
        $sort = Request::param('sort');
        $order = '';
        if ($sort) {
            foreach ($sort as $key => $value) {
                if ($value) {
                    $order = $key.' '.$value ?: $order.','.$key.' '.$value;
                }
            }
        }
        $pk = $this->model->getPk();
        $order = $order ?: $pk.' desc';
        $page  = Request::param('page/d', 1);
        
        $limit = Request::param('limit/d', 15);
        
        return [$where, $order, $page, $limit];
    }
    /**
     * 新增
     */
    public function add($data = null)
    {
        if (Request::isPost()){
            $data = $data ?? Request::param();
            //验证
            if (!empty($this->validate_class)){
                $validate_class = $this->validate_class;
                if (!strpos($this->validate_class, '.')) {
                    $validate_class = $validate_class.'.add';
                }
                $re = $this->validate($data,$validate_class);
                if (true!==$re){
                    $this->error($re['msg']);
                }
            }
            //$this->error('xxx');
            $this->model->save($data);
            $this->success('添加成功');
        }
        return $this->form
                    ->setPageTitle('添加'.$this->form->getVars('page_title'))
                    ->fetch();
    }
    /**
     * 编辑
     */
    public function edit($id, $data = null)
    {
        $info = $this->model->find($id);
        if (!$info){
            return $this->error('数据不存在');
        }
        if (Request::isPost()){
            $data = $data ?? Request::param();
            //验证
            if (!empty($this->validate_class)){
                $validate_class = $this->validate_class;
                if (!strpos($this->validate_class, '.')) {
                    $validate_class = $validate_class.'.edit';
                }
                $re = $this->validate($data,$validate_class);
                if (true!==$re){
                    $this->error($re['msg']);
                }
            }
            $info->save($data);
            //后置操作
            if (method_exists($this, 'after_edit')){
                $this->after_edit($info);
            }
            return $this->success('更新成功');
        }
        //主键非id情况
        $pk = $this->model->getPk();
        if ($pk!='id'){
            $info['id'] = $info[$pk];
        }
        $this->assign('info',$info);
        return $this->form
                    ->setPageTitle('编辑'.$this->form->getVars('page_title'))
                    ->setDataUrl('edit')
                    ->fetch();
    }
    
    
    /**
     * 删除
     */
    public function delete()
    {
        $this->setStatus('delete');
    }
    /**
     * 设置状态
     * 禁用、启用、删除都是调用这个内部方法
     * @param string $type 操作类型：enable,disable,delete
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function setStatus($type = '')
    {
        $ids   = $this->request->isPost() ? input('post.ids/a') : input('param.ids');
        $ids   = (array)$ids;
        $field = input('param.field', 'status');
        
        empty($ids) && $this->error('请选择要操作的数据');
        
        $Model = $this->model;
        $protect_table = [
            config('database.connections.mysql.prefix').'admin' => '1',
            config('database.connections.mysql.prefix').'auth_rule' => '1',
            config('database.connections.mysql.prefix').'auth_group' => '1',
            config('database.connections.mysql.prefix').'config' => '1,2',
        ];
        
        // 禁止操作核心表的主要数据
        if (isset($protect_table[$Model->getTable()]) && count(array_intersect(str2arr($protect_table[$Model->getTable()]), $ids))>0 ){
        //if (in_array($Model->getTable(), $protect_table) && in_array('1', $ids)) {
            $this->error('受保护数据,禁止操作');
        }
        // 主键名称
        $pk = $Model->getPk();
        
        
        $result = false;
        switch ($type) {
            case 'disable': // 禁用
                $result = $Model->where($pk,'in',$ids)->data([$field=>0])->update();
                break;
            case 'enable': // 启用
                $result = $Model->where($pk,'in',$ids)->data([$field=>1])->update();
                break;
            case 'delete': // 删除
                $result = $Model->where($pk,'in',$ids)->delete();
                break;
            default:
                $this->error('非法操作');
                break;
        }
        
        if (false !== $result) {
            
            $this->success('操作成功');
        } else {
            $this->error('操作失败');
        }
    }
}