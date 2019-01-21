<?php
/**
 * Introduce ：
 * Created by Zyp.
 * Date: 2018/9/25
 */
namespace app\controllers;

use app\components\ErrorHttpException;
use app\controllers\BaseController as Controller;
use app\models\Models;
use app\models\WhPlanList;
use app\models\WhStore;
use app\utility\Cache;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;


Class TestController extends Controller
{
    public $enableCsrfValidation = false;
    private $user;
    private $pwd;

    public function actionDeal($a)
    {
        return $a  ;
    }


    public function actionTest()
    {
        $model = new WhPlanList();
        $list = $model->getList([],[],20,0);
        $store = new WhStore();
        $store_list = $store->getList([],['id'=>false],0,0);
        foreach ($list as $k => $v )
        {
            $ret_store = $this->Array_filter($store_list,$v['store_id'],'id');
            $list[$k]['store_name'] = $ret_store[0]['name'];
        }
    }

    public function actionTest1()
    {
        $model = new WhPlanList();
        $list = $model->getList([],['id'=>false],20,0);
        $store = new WhStore();
        $store_list = array_column($store->getList([],['id'=>false],0,0),null,'id');

        $list = $this->UnsetEmpty($list,'store_id');
        foreach ($list as $k => $v)
        {
            $list[$k]['store_name'] = $store_list[$v['store_id']]['name'];
        }
    }

    public function Array_filter($data,$id,$key)
    {
        if(!is_array($data) || !$data )
        {
            return '';
        }

        $ret = array_filter($data,function ($var)use($id,$key){
            if($var[$key])
            {
                return $id==$var[$key];
            }
        });

        return array_values($ret);
    }

    public function UnsetEmpty($data,$key)
    {
        if(!is_array($data) || !$data )
        {
            return '';
        }
//        var_dump($data);
        $ret = array_filter($data,function ($var)use($key)
        {
            if(!$var[$key])
            {
                return '';
            }
            return true;

        });
        return $ret;
    }

    public function actionTestRedis()
    {
        $cache = new Cache('cache');
//        var_dump($cache->setCache('test','这个是yiicache'));
//        var_dump($cache->getCache('test'));
        var_dump($cache->delCache('test'));
    }
}
