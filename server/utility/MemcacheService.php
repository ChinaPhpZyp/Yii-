<?php
/**
 * Introduce ：memcache服务类
 * Created by Zyp丶.
 * Date: 2019/1/21
 */
namespace app\utility;

Class MemcacheService
{
    private static $_instance ;

    private function __construct(){}

    private function __clone(){}

    public static function getInstance()
    {
        if(self::$_instance instanceof MemcacheService)
        {
            return self::$_instance;
        }
            return new MemcacheService();
    }

    public function getCache($key)
    {
        return \Yii::$app->memcache->get($key);
    }

    public function setCache($key,$value)
    {
        return \Yii::$app->memcache->set($key,$value);
    }

    public function delCache($key)
    {
        return \Yii::$app->memcache->delete($key);
    }
}