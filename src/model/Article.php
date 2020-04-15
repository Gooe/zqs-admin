<?php
namespace zqs\admin\model;

use think\Model;

class Article extends Model
{
    public function getCoverUrlAttr($value,$data)
    {
        return get_file_path($data['cover']);
    }
    public function getCateTextAttr($value,$data){
        return ArticleCate::where('id',$data['cate_id'])->value('name');
    }
}
