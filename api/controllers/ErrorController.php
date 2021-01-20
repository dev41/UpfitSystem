<?php
namespace api\controllers;

use app\src\exception\ApiException;
use app\src\library\BaseApiController;

class ErrorController extends BaseApiController
{
    public function behaviors()
    {
        return [];
    }

    public function actionIndex()
    {
        /** @var ApiException $exception */
        $exception = \Yii::$app->errorHandler->exception;

        $response = [
            'message' => $exception->getMessage(),
            'code' => $exception->getCode(),
        ];

        $response = array_merge($response, $exception->data);
        return $this->response($response, false);
    }
}