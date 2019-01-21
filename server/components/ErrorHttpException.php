<?php
/**
 * Introduce ：抛出错误类
 * Created by Zyp.
 * Date: 2018/9/27
 */
namespace app\components;

use yii\web\HttpException;
use yii\base\Exception;
use yii\base\UserException;

class ErrorHttpException extends HttpException
{
    /**
     * Constructor.
     * @param string $message error message
     * @param int $code error code
     * @param \Exception $previous The previous exception used for the exception chaining.
     */
    public function __construct($message = null,$code = 301,$status = 1, $previous = null)
    {
        parent::__construct($code, $message, $status, $previous);
    }
}