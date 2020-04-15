<?php
namespace zqs\admin\model;

use think\Model;

class ArticleCate extends Model
{
    public function getCoverUrlAttr($value,$data)
    {
        return get_file_path($data['cover']);
    }
}
