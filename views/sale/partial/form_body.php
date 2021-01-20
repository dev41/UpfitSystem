<?php

use app\src\entities\sale\Sale;
use app\src\entities\place\Club;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use kartik\datecontrol\DateControl;
use app\src\helpers\FormHelper;

/* @var Sale $sale */
/* @var Club[] $clubs */
?>

<?= $form->field($sale, 'club_id')->widget(Select2::class, [
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

<?= $form->field($sale, 'name')->label(\Yii::t('app', 'Name')) ?>

<?= $form->field($sale, 'deleteImages[]', ['template' => '{input}'])->hiddenInput()->label(false) ?>

<?= FormHelper::getImagesField($form, $sale, [
    'label' => 'Image',
    'jsClass' => 'js-images',
    'fieldName' => 'images[]',
    'extPath' => '',
]); ?>

<?= FormHelper::getDescriptionField($form, $sale, [
    'widgetConfig' => [
        'options' => ['rows' => 4],
    ]
]); ?>

<?= $form->field($sale, 'start')->widget(DateControl::class, [
    'type' => DateControl::FORMAT_DATETIME,
    'options' => [
        'placeholder' => 'Select start',
    ],
    'autoWidget' => true,
])->label(\Yii::t('app', 'Start')); ?>

<?= $form->field($sale, 'end')->widget(DateControl::class, [
    'type' => DateControl::FORMAT_DATETIME,
    'options' => ['placeholder' => 'Select start'],
    'autoWidget' => true,
])->label(\Yii::t('app', 'End')); ?>