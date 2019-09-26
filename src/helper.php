<?php
if (! function_exists('get_view_path')) {
    /**
     * 获取模板具体目录.
     *
     * @return string
     */
    function get_view_path()
    {
        return __DIR__.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR;
    }
}