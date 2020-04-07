<?php

namespace zqs\admin\model;

use think\Model;
use think\facade\Cache;

class AuthRule extends Model
{
    public static function onBeforeUpdate($menu)
    {
        $ismenu = input('ismenu');
        if (empty($ismenu)){
            $menu->ismenu = 0;
        }
    }
    public static function onAfterUpdate($menu)
    {
        Cache::delete('__menu__');
    }
    public static function onAfterInsert($menu)
    {
        Cache::delete('__menu__');
    }
}
