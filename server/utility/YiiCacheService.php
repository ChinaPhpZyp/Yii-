<?php
/**
 * Introduce ：yii2文件缓存服务类
 * Created by Zyp丶.
 * Date: 2019/1/21
 */
namespace app\utility;

Class YiiCacheService
{
    private static $_instance ;

    private function __construct(){}

    private function __clone(){}

    public static function getInstance()
    {
        if(self::$_instance instanceof YiiCacheService)
        {
            return self::$_instance;
        }
            return new YiiCacheService();
    }

    public function getCache($key)
    {
        return \Yii::$app->cache->get($key);
    }

    public function setCache($key,$value)
    {
        return \Yii::$app->cache->set($key,$value);
    }

    public function delCache($key)
    {
        return \Yii::$app->cache->delete($key);
    }
}