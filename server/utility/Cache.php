<?php
/**
 * Introduce ：缓存类
 * Created by Zyp丶.
 * Date: 2019/1/21
 */
namespace app\utility;
use app\hickey\CacheFactory;

Class Cache implements CacheFactory
{
    public $_type ;

    public function __construct($model)
    {
        switch ($model)
        {
            case 'redis' :
                $this->_type = RedisService::getInstance();
                break;
            case 'memcache' :
                $this->_type = MemcacheService::getInstance();
                break;
            case 'cache' :
                $this->_type = YiiCacheService::getInstance();
                break;
        }
    }

    public function getCache($key)
    {
        return $this->_type->getCache($key);
    }

    public function setCache($key,$value)
    {
        return $this->_type->setCache($key,$value);
    }

    public function delCache($key)
    {
        return $this->_type->delCache($key);
    }
}