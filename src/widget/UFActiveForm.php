<?php

namespace app\src\widget;

use app\src\entities\AbstractModel;
use app\src\helpers\InputHelper;
use yii\widgets\ActiveField;
use yii\widgets\ActiveForm;

class UFActiveForm extends ActiveForm
{
    public static $formNameHash;

    public static function generateFormNameHash()
    {
        self::$formNameHash = sha1(time());
    }

    public static function begin($config = [])
    {
        self::generateFormNameHash();
        return parent::begin(array_merge_recursive($config, [
            'options' => ['data-hash' => self::$formNameHash],
        ]));
    }

    public function hiddenInput($model, $attribute, array $options = null)
    {
        if (!$options) {
            $options = ['template' => '{input}'];
        }
        return $this->field($model, $attribute, $options)->hiddenInput()->label(false);
    }

    public function textInput(
        AbstractModel $model,
        string $attribute,
        array $inputOptions = [],
        int $type = InputHelper::TYPE_FACEBOOK,
        array $fieldOptions = []
    ): ActiveField
    {
        return InputHelper::textInput($this, $model, $attribute, $type, $inputOptions, $fieldOptions);
    }
}