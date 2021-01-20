<?php
namespace app\src\exception;

use app\src\entities\AbstractModel;
use yii\base\UserException;

/**
 * Class ModelValidateException
 */
class ModelValidateException extends UserException
{
    public $model;

    public function __construct(AbstractModel $model, $errors = null)
    {
        $this->model = $model;

        $this->message = json_encode([
            'errors' => $errors ?? $model->getErrors(),
            'model' => $model::getShortClassName(),
        ]);
    }
}