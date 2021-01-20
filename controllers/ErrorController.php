<?php
namespace app\controllers;

use app\src\library\BaseApiController;

class ErrorController extends BaseApiController
{
    public function behaviors()
    {
        return [];
    }

    public function actionIndex()
    {
        $exception = \Yii::$app->errorHandler->exception;

        return $this->response([
            'message' => $exception->getMessage(),
            'code' => $exception->getCode(),
        ], false);
    }
}