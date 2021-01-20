<?php

use app\src\entities\activity\Activity;
use app\src\entities\place\Club;
use app\src\entities\user\User;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use kartik\datecontrol\DateControl;
use app\src\helpers\FormHelper;

/* @var Activity $activity */
/* @var Club[] $clubs */
/* @var User[] $organizers */
?>
<?= $form->field($activity, 'club_id')->widget(Select2::class, [
    'data' => ArrayHelper::map($clubs, 'id', 'name'),
    'options' => [
        'multiple' => false,
        'prompt' => Yii::t('app', 'select club'),
    ],
    'pluginOptions' => [
        'tags' => true,
        'tokenSeparators' => [','],
    ],
])->label(Yii::t('app', 'Club')) ?>

<?= $form->field($activity, 'name')->label(Yii::t('app', 'Name')) ?>

<?= $form->field($activity, 'deleteImages[]', ['template' => '{input}'])->hiddenInput()->label(false) ?>

<?= FormHelper::getImagesField($form, $activity, [
    'label' => 'Image',
    'jsClass' => 'js-images',
    'fieldName' => 'images[]',
    'extPath' => '',
]); ?>

<?= FormHelper::getDescriptionField($form, $activity, [
    'widgetConfig' => [
        'options' => ['rows' => 4],
    ]
]); ?>

<?= $form->field($activity, 'price')->label(Yii::t('app', 'Price')) ?>

<?= $form->field($activity, 'capacity')->label(Yii::t('app', 'Capacity')) ?>

<?= $form->field($activity, 'start')->widget(DateControl::class, [
    'type' => DateControl::FORMAT_DATETIME,
    'options' => [
        'placeholder' => 'Select start',
    ],
    'autoWidget' => true,
])->label(Yii::t('app', 'Start')); ?>

<?= $form->field($activity, 'end')->widget(DateControl::class, [
    'type' => DateControl::FORMAT_DATETIME,
    'options' => ['placeholder' => 'Select start'],
    'autoWidget' => true,
])->label(Yii::t('app', 'End')); ?>

<?= $form->field($activity, 'organizers')->widget(Select2::class, [
    'data' => ArrayHelper::map($organizers, 'id', 'username'),
    'options' => [
        'multiple' => true,
        'prompt' => Yii::t('app', 'select organizers'),
    ],
    'pluginOptions' => [
        'tags' => true,
        'tokenSeparators' => [','],
    ],
])->label(Yii::t('app', 'Organizers')) ?>