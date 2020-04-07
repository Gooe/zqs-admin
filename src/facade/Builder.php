<?php
namespace zqs\admin\facade;

use think\Facade;

class Builder extends Facade
{
    protected static function getFacadeClass()
    {
        return 'zqs\admin\builder\Builder';
    }
}
