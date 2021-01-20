<?php

use app\src\entities\place\Place;
use app\src\entities\place\Club;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/* @var Place $place */
/* @var Club[] $clubs */
/* @var array $types */
?>
<?= $form->field($place, 'name')->label(\Yii::t('app', 'Name')) ?>

<?= $form->field($place, 'parent_id')->widget(Select2::class, [
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

<?= $form->field($place, 'type')->widget(Select2::class, [
    'data' => $types,
    'options' => [
        'multiple' => false,
        'prompt' => \Yii::t('app', 'select type'),
    ],
    'pluginOptions' => [
        'tags' => true,
        'tokenSeparators' => [','],
    ],
])->label(\Yii::t('app', 'Type')) ?>

<?= $form->field($place, 'deleteImages[]', ['template' => '{input}'])->hiddenInput()->label(false) ?>

<?= \app\src\helpers\FormHelper::getImagesField($form, $place, [
    'label' => 'Image',
    'jsClass' => 'js-images',
    'fieldName' => 'images[]',
    'modelName' => 'place',
    'widgetConfig' => [
        'options' => ['multiple' => true],
    ],
    'extPath' => ''
]); ?>

<?= $form->field($place, 'description')->label(\Yii::t('app', 'Description'))->textarea(['rows' => 4]) ?>
<?= $form->field($place, 'address')->label(\Yii::t('app', 'Address')) ?>
<?= $form->field($place, 'active')->label(\Yii::t('app', 'Active'))->checkbox(['class' => null]) ?>
