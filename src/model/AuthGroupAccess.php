<?php

namespace zqs\admin\model;

use think\Model;

class AuthGroupAccess extends Model
{
	// 关闭自动写入update_time字段
	protected $updateTime = false;
	protected $createTime = false;
}
