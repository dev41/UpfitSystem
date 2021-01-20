<?php
namespace app\src\helpers;

use app\src\entities\AbstractModel;
use app\src\widget\UFActiveForm;
use yii\widgets\ActiveField;

class InputHelper
{
    const TYPE_EMAIL = 1;
    const TYPE_FACEBOOK = 2;
    const TYPE_PASSWORD = 3;

    const WRAPPER_TYPE_ICON = 1;
    const WRAPPER_TYPE_HTML = 2;

    const ICON_EMAIL = 'envelope';

    public static function textInput(
        UFActiveForm $form,
        AbstractModel $model,
        string $attribute,
        int $type,
        array $inputOptions = [],
        array $fieldOptions = []
    ): ActiveField
    {
        $after = [];
        $before = [];

        switch ($type) {
            case self::TYPE_EMAIL:
                $before['type'] = self::WRAPPER_TYPE_ICON;
                $before['value'] = self::ICON_EMAIL;
                break;
            case self::TYPE_FACEBOOK:
                $before['type'] = self::WRAPPER_TYPE_HTML;
                $before['value'] = 'https://facebook.com/';
                break;
                case self::TYPE_PASSWORD:
                $after['type'] = self::WRAPPER_TYPE_HTML;
                $after['value'] = '<span class="glyphicon glyphicon-eye-open password-eye js-password-eye"></span>';
                break;
        }

        $template = \Yii::$app->controller->renderPartial('/_helpers/input/text', [
            'after' => $after,
            'before' => $before,
        ]);

        $fieldOptions = $fieldOptions + ['template' => $template];

        return $form->field($model, $attribute, $fieldOptions)->textInput($inputOptions);
    }
}