<?php
namespace zqs\admin\facade;

use think\Facade;

/**
 * Class AuthAdmin
 * @package zqs\admin\facade
 * @mixin \zqs\admin\lib\AuthAdmin
 * @see \zqs\admin\lib\AuthAdmin
 */
class AuthAdmin extends Facade
{
    protected static function getFacadeClass()
    {
        return 'zqs\admin\lib\AuthAdmin';
    }
}
