<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{


//    public function actionError()
//    {
//        $exception = \Yii::$app->errorHandler->exception;
//        if ($exception instanceof \Exception) {
//            header('Content-type: application/json', true, 200);
//            $error_message = ['status' => 1,
//                              'data' => '',
//                              'error' => $exception->getMessage()];
//            if (YII_ENV_DEV) {
//                $error_message['debug'] = [
//                    '_POST' => $_POST,
//                    '_GET' => $_GET,
//                ];
//            }
//            echo json_encode($error_message);
//        } else {
//            header('Content-type: application/json', true, 200);
//            echo json_encode(['code' => 500, 'data' => []]);
//        }
//    }
}
