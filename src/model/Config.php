<?php
namespace zqs\admin\model;
use think\Model;

class Config extends Model
{
    public static function onAfterInsert($model){
        cache('__db_config__',null);
    }
    public static function onAfterDelete($model){
        cache('__db_config__',null);
    }
    public static function onAfterUpdate($model){
        cache('__db_config__',null);
    }
}