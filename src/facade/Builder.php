<?php
namespace zqs\admin\facade;

use think\Facade;

/**
 * Class Builder
 * @package zqs\admin\facade
 * @see \zqs\admin\builder\Builder
 * @mixin \zqs\admin\builder\Builder
 */
class Builder extends Facade
{
    protected static function getFacadeClass()
    {
        return 'zqs\admin\builder\Builder';
    }
}
