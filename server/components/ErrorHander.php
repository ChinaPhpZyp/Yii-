<?php
/**
 * Introduce ：错误抛出类
 * Created by Zyp.
 * Date: 2018/9/27
 */
namespace app\components;

use yii\base\UserException;
use yii\web\ErrorHandler;
use Yii;
use yii\web\Response;

/**
* Class ErrorHandler
* @package deepziyu\yii\rest
*/
class ErrorHander extends \yii\web\ErrorHandler
{
    /**
     * @var integer maximum number of source code lines to be displayed. Defaults to 19.
     */
    public $maxSourceLines = 10;
    /**
     * @var integer maximum number of trace source code lines to be displayed. Defaults to 13.
     */
    public $maxTraceSourceLines = 5;

    /**
     * Renders the exception.
     * @param \Exception $exception the exception to be rendered.
     */
    protected function renderException($exception)
    {
        if (Yii::$app->has('response')) {
            $response = Yii::$app->getResponse();
            // reset parameters of response to avoid interference with partially created response data
            // in case the error occurred while sending the response.
            $response->isSent = false;
            $response->stream = null;
            $response->data = null;
            $response->content = null;
        } else {
            $response = new Response();
        }

        $useErrorView = $response->format === Response::FORMAT_HTML && (!YII_DEBUG || $exception instanceof UserException);

        if ($useErrorView && $this->errorAction !== null) {
            $result = Yii::$app->runAction($this->errorAction);
            if ($result instanceof Response) {
                $response = $result;
            } else {
                $response->data = $result;
            }
        } elseif ($response->format === Response::FORMAT_HTML) {
            if (YII_ENV_TEST || isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                // AJAX request
                $response->data = '<pre>' . $this->htmlEncode(static::convertExceptionToString($exception)) . '</pre>';
            } else {
                // if there is an error during error rendering it's useful to
                // display PHP error in debug mode instead of a blank screen
                if (YII_DEBUG) {
                    ini_set('display_errors', 1);
                }
                $file = $useErrorView ? $this->errorView : $this->exceptionView;
                $response->data = $this->renderFile($file, [
                    'exception' => $exception,
                ]);
            }
        } elseif ($response->format === Response::FORMAT_RAW) {
            $response->data = static::convertExceptionToString($exception);
        } else {
            $response->data = $this->convertExceptionToArray($exception);
        }

        $response->setStatusCode(201);

        $response->send();
    }

    /**
     * Converts an exception into an array.
     * @param \Exception $exception the exception being converted
     * @return array the array representation of the exception.
     */
    protected function convertExceptionToArray($exception)
    {
        $response = \Yii::$app->response;
        $request = \Yii::$app->request;
        $statusCode = $exception->statusCode;

        $array = [
            'status' =>  1,
            'code' =>  $statusCode ? $statusCode : $statusCode===0 ? 0 : 201,
            'data' => new \stdClass(),
            'error' => $exception->getMessage(),
            'timestamp' => date('Y-m-d H:i:s',time()),
        ];


        if($exception instanceof EmptyException) {
            $array['status'] = 0;
            $array['data'] = $exception->data ? $exception->data : $this->emptyObject();
            $array['code'] =$statusCode ? $statusCode :  201;
        }

        if ($exception instanceof HttpException ) {
            $array['status'] = $exception->getCode();
        }
        if (YII_DEBUG) {
            //            if (!$exception instanceof UserException && !$exception instanceof ApiException) {
            $array['file'] = $exception->getFile();
            $array['line'] = $exception->getLine();
            $array['stack-trace'] = explode("\n", $exception->getTraceAsString());
            if ($exception instanceof \yii\db\Exception) {
                $array['error-info'] = $exception->errorInfo;
            }
            //            }
            if (($prev = $exception->getPrevious()) !== null) {
                // $array['previous'] = $this->convertExceptionToArray($prev);
            }
            $post_data = $request->post();
            unset($post_data['password']);
            unset($post_data['current_password']);
            unset($post_data['verifyCode']);
            $array['debug']['_POST'] = $post_data ? $post_data :  $this->emptyObject();
            $array['debug']['_GET'] = $request->get() ? $request->get() : $this->emptyObject();
            $array['debug']['url'] = '/' .\Yii::$app->request->getPathInfo();
        }
        return $array;
    }

    protected function emptyObject() {
        return new \stdClass();
    }

    /**
     * Returns human-readable exception name
     * @param \Exception $exception
     * @return string human-readable exception name or null if it cannot be determined
     */
    public function getExceptionName($exception)
    {
        if ($exception instanceof \yii\base\Exception || $exception instanceof \yii\base\InvalidCallException || $exception instanceof \yii\base\InvalidParamException || $exception instanceof \yii\base\UnknownMethodException) {
            return $exception->getName();
        }
        return null;
    }

}
