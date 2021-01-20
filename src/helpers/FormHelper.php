<?php
namespace app\src\helpers;

use app\src\entities\AbstractModel;
use app\src\entities\IImagesFieldModel;
use dosamigos\ckeditor\CKEditor;
use kartik\file\FileInput;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

class FormHelper
{
    const ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'png', 'gif'];
    const INITIAL_PREVIEW_AS_DATA = true;
    const OVERWRITE_INITIAL = false;

    /**
     * @param ActiveForm $form
     * @param AbstractModel|IImagesFieldModel $model
     * @param array $options
     * @return \yii\widgets\ActiveField
     */
    public static function getImagesField(ActiveForm $form, AbstractModel $model, array $options = [])
    {
        $field = $options['field'] ?? $model->getImageField();
        $logo = ImageHelper::getImagesInfo($model, $field, $options['extPath']);

        $fieldName = $options['fieldName'] ?? 'logo[]';
        $label = $options['label'] ?? 'Logo';
        $jsClass = $options['jsClass'] ?? 'js-logo';
        $widgetConfig = $options['widgetConfig'] ?? [];
        $modelName = $options['modelName'] ?? lcfirst($model::getShortClassName());

        $template = $options['template'] ?? [] ;

        $defaultWidgetConfigs = [
            'options' => [
                'accept' => 'image/*',
                'multiple' => false,
                'class' => $jsClass,
                'data-confirm-message' => \Yii::t('app', 'Are you sure you want to delete this ') . lcfirst($label) . '?',
                'data-notification-message' => lcfirst($label) . \Yii::t('app', ' have been successfully deleted.'),
            ],
            'pluginOptions' => [
                'deleteUrl' => Url::to([$modelName . '/delete-image',
                    'id' => $model['id'],
                    'filename' => $logo['info'][0]['filename'] ?? '',
                    'extPath' => $options['extPath']
                ]),
                'initialPreview' => isset($logo['url']) ? $logo['url'] : '',
                'initialPreviewAsData' => self::INITIAL_PREVIEW_AS_DATA,
                'overwriteInitial' => self::OVERWRITE_INITIAL,
                'initialPreviewConfig' => isset($logo['info']) ? $logo['info'] : '',
                'allowedFileExtensions' => self::ALLOWED_EXTENSIONS,
            ],
        ];

        $fieldConfig = [
            'options' => ['class' => 'row ' . $jsClass]
        ];

        if (!empty($template)) $fieldConfig['template'] = $template;

        return $form->field($model, $fieldName, $fieldConfig)
            ->label(\Yii::t('app', $label), ['class' => 'img_label'])
            ->widget(FileInput::class, array_merge_recursive($defaultWidgetConfigs, $widgetConfig));

    }

    /**
     * @param ActiveForm $form
     * @param AbstractModel|IImagesFieldModel $model
     * @param array $options
     * @return \yii\widgets\ActiveField
     */
    public static function getDescriptionField(ActiveForm $form, AbstractModel $model, array $options = [])
    {
        $fieldName = $options['fieldName'] ?? 'description';
        $label = $options['label'] ?? 'Description';
        $widgetConfig = $options['widgetConfig'] ?? [];
        $useCKEditor = $options['useCKEditor'] ?? false;
        $template = $options['template'] ?? [];

        $fieldConfig = [
            'options' => ['class' => 'row ']
        ];

        if (!empty($template)) $fieldConfig['template'] = $template;

        $formField = $form
            ->field($model, $fieldName, $fieldConfig)
            ->label(\Yii::t('app', $label));

        $formField = $useCKEditor
            ? $formField->widget(CKEditor::class, $widgetConfig)
            : $formField->textarea($widgetConfig['options']);

        return $formField;
    }
}