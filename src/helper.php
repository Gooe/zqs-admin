<?php
use think\facade\Db;

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

if (! function_exists('get_file_path')){
    /**
     * 获取文件路径
     */
    function get_file_path($idarr, $field = ''){
        $default_img = '/static/common/images/default_image.gif';
        if(empty($idarr)){
            return $default_img;
        }
        if (strpos($idarr, ',')!==false){//多图
            $pictureList = Db::name('attachment')->where('status',1)->where('id','in',$idarr)->field($field)->select();
            return $pictureList;
        }else {//单图
            $picture = Db::name('attachment')->where(['status'=>1,'id'=>$idarr])->find();
            if (!$picture){
                return $default_img;
            }
            return empty($field) ? $picture['url'] : $picture[$field];
        }
    }
}
if (!function_exists('parse_attr')) {
    /**
     * 解析配置
     * @param string $value 配置值
     * @return array|string
     */
    function parse_attr($value = '') {
        $array = preg_split('/[,;\r\n]+/', trim($value, ",;\r\n"));
        if (strpos($value, ':')) {
            $value  = array();
            foreach ($array as $val) {
                list($k, $v) = explode(':', $val);
                $value[$k]   = $v;
            }
        } else {
            $value = $array;
        }
        return $value;
    }
}

if (! function_exists('config_db')){
    /**
     * 获取数据库中的配置
     * @param string $name
     */
    function config_db($name=''){
        $configs = Db::name('config')->cache('__db_config__')->column('value,type', 'name');
        $result = [];
        foreach ($configs as $config) {
            switch ($config['type']) {
                case 'array':
                    $result[$config['name']] = parse_attr($config['value']);
                    break;
                case 'checkbox':
                    if ($config['value'] != '') {
                        $result[$config['name']] = explode(',', $config['value']);
                    } else {
                        $result[$config['name']] = [];
                    }
                    break;
                default:
                    $result[$config['name']] = $config['value'];
                    break;
            }
        }
        
        return $name != '' ? $result[$name] : $result;
    }
}































