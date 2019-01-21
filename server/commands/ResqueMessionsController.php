<?php
/**
 * Introduce ：任务队列
 * Created by Zyp.
 * Date: 2018/10/15
 */
namespace app\commands;

use yii\console\Controller;

Class ResqueMessionsController extends  Controller
{
    public function actionTest()
    {
        sleep(2);
        echo 'hello';
    }
}