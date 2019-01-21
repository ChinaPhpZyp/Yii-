<?php
/**
 * Introduce ：缓存工厂类
 * Created by Zyp丶.
 * Date: 2019/1/21
 */
namespace app\hickey;

interface CacheFactory
{
    public function getCache($key);

    public function setCache($key,$value);

    public function delCache($key);
}