<?php
namespace zqs\admin\facade;

use think\Facade;

class AuthAdmin extends Facade
{
    protected static function getFacadeClass()
    {
        return 'zqs\admin\lib\AuthAdmin';
    }
}
