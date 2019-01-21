<?php
/**
 * Introduce ：redis服务类
 * Created by Zyp丶.
 * Date: 2019/1/21
 */
namespace app\utility;

Class RedisService
{
    private static $_instance ;

    private function __construct(){}

    private function __clone(){}

    public static function getInstance()
    {
        if(self::$_instance instanceof RedisService)
        {
            return self::$_instance;
        }
            return new RedisService();
    }

    public function getCache($key)
    {
        return \Yii::$app->redis->get($key);
    }

    public function setCache($key,$value)
    {
        return \Yii::$app->redis->set($key,$value);
    }

    public function delCache($key)
    {
        return \Yii::$app->redis->del($key);
    }
}