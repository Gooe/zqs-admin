<?php

namespace zqs\admin\model;

use think\Model;

class AdminLog extends Model
{
    // 关闭自动写入update_time字段
    protected $updateTime = false;
    
    public static function record($title = '')
    {
        $admin = session('admin');
        $admin_id = $admin ? $admin->uid : 0;
        $username = $admin ? $admin->username : 'Unknown';
        $content = request()->param();
        foreach ($content as $k => $v)
        {
            if (is_string($v) && strlen($v) > 200)
            {
                unset($content[$k]);
            }
        }
        $title = [];
        $breadcrumb = \app\admin\library\Auth::instance()->getBreadcrumb();
        foreach ($breadcrumb as $k => $v)
        {
            $title[] = $v['title'];
        }
        self::create([
            'title'     => implode('>', $title),
            'content'   => json_encode($content),
            'url'       => request()->url(),
            'admin_id'  => $admin_id,
            'username'  => $username,
            'useragent' => request()->server('HTTP_USER_AGENT'),
            'ip'        => request()->ip()
        ]);
    }

    public function admin()
    {
        return $this->belongsTo('Admin', 'admin_id')->setEagerlyType(0);
    }

}
