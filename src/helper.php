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
if (! function_exists('get_admin_path')) {
    /**
     * 获取应用目录
     *
     * @return string
     */
    function get_admin_path()
    {
        return config('admin.admin_path')?:'admin';
    }
}
if (! function_exists('get_admin_name')) {
    /**
     * 获取应用实际访问名称【可能有别名】
     *
     * @return string
     */
    function get_admin_name()
    {
        $name = get_admin_path();
        $app_map = config('app.app_map');
        if (!empty($app_map)){
            foreach ($app_map as $k=>$v){
                if ($v==$name){
                    $name = $k;
                }
            }
        }
        return $name;
    }
}
if (! function_exists('str2arr')) {
    /**
     * 字符串转换为数组，主要用于把分隔符调整到第二个参数
     * @param  string $str  要分割的字符串
     * @param  string $glue 分割符
     * @return array
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    function str2arr($str, $glue=',')
    {
        return explode($glue, $str);
    }
}
if (! function_exists('arr2str')) {
    /**
     * 数组转换为字符串，主要用于把分隔符调整到第二个参数
     * @param  array  $arr  要连接的数组
     * @param  string $glue 分割符
     * @return string
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    function arr2str($arr, $glue = ',')
    {
        return implode($glue, $arr);
    }
}