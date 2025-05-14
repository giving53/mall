<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2019 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\facade\App;
use think\facade\Env;

// [ 应用入口文件 ]

// 定义应用目录
define('APP_PATH', __DIR__ . '/../app/');

// 加载 Composer 自动加载
require __DIR__ . '/../vendor/autoload.php';

// 加载框架引导文件
// require __DIR__ . '/../thinkphp/start.php';

// 启动框架应用并发送响应
App::run()->send();

// 结束应用生命周期
App::end($response);

