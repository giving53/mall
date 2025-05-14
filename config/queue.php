<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: yunwuxin <448901948@qq.com>
// +----------------------------------------------------------------------

return [
    // 使用队列的连接
    'default' => 'redis',

    'connections' => [
        'redis' => [
            'driver' => 'redis',
            'host'   => '127.0.0.1',
            'port'   => 6379,
            'password' => '',
            'queue'  => 'default',
            'expire' => 60,
            'timeout' => 5,
        ],
    ],

    'failed' => [
        'driver' => 'database',
        'database' => 'queue_failed',
        'table' => 'failed_jobs',
    ],
];