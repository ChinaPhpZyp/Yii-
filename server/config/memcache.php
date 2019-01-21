<?php
/**
 * Introduce ：memcache配置
 * Created by Zyp丶.
 * Date: 2019/1/21
 */

return [
    'class' => 'yii\caching\MemCache',
    'useMemcached' => true,
    'servers' => [
//        ['host' => '47.98.255.48', 'port' => 11211, 'weight' => 100,],
                ['host' => '127.0.0.1','port' => 11211 , 'weight' => 100]
    ],
];