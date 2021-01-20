<?php

use app\src\entities\news\News;
use app\src\entities\place\Club;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\src\entities\translate\Language;
use app\src\helpers\FormHelper;

if (Yii::$app->language == Language::DEFAULT_LANGUAGE) {
    $language = '';
} else {
    $language = Yii::$app->language;
}

/* @var News $news */
/* @var Club[] $clubs */
?>
<?= $form->field($news, 'club_id')->widget(Select2::class, [
    'data' => ArrayHelper::map($clubs, 'id', 'name'),
    'options' => [
        'multiple' => false,
        'prompt' => \Yii::t('app', 'select club'),
    ],
    'pluginOptions' => [
        'tags' => true,
        'tokenSeparators' => [','],
    ],
])->label(\Yii::t('app', 'Club')) ?>

<?= $form->field($news, 'name')->label(\Yii::t('app', 'Name')) ?>
<?= $form->field($news, 'deleteImages[]', ['template' => '{input}'])->hiddenInput()->label(false) ?>

<?= FormHelper::getImagesField($form, $news, [
    'label' => 'Image',
    'jsClass' => 'js-images',
    'fieldName' => 'images[]',
    'extPath' => '',
]); ?>

<?= FormHelper::getDescriptionField($form, $news, [
    'widgetConfig' => [
        'options' => ['rows' => 4],
    ]
]); ?>

<?= $form->field($news, 'active')->label(\Yii::t('app', 'Active'))->checkbox(['class' => null]) ?>