<?php

use app\src\entities\staff\StaffPositionPlace;
use app\src\entities\user\Position;
use app\src\widget\UFActiveForm;
use yii\web\View;
use kartik\select2\Select2;
use yii\helpers\Html;

/**
 * @var View $this
 * @var string $processButtonTitle
 * @var StaffPositionPlace $staffPositionPlace
 * @var array $availableStaff
 */

$renderAjax = $renderAjax ?? false;
$processButtonTitle = $processButtonTitle ?? ($staffPositionPlace->isNewRecord ?
        \Yii::t('app', 'Add Staff') :
        \Yii::t('app', 'Update Staff'));

$form = UFActiveForm::begin(['id' => 'staff-position-place-form']) ?>


<?php
if ($staffPositionPlace->isNewRecord) {
    echo $form->field($staffPositionPlace, 'user_id')->widget(Select2::class, [
        'data' => $availableStaff,
        'options' => [
            'id' => 'spp_user_id',
            'multiple' => false,
            'prompt' => \Yii::t('app', 'select name'),
        ],
    ])->label(\Yii::t('app', 'Username'));
} else {
    echo $form->field($staffPositionPlace, 'user_id', ['template' => '{input}'])->hiddenInput()->label(false);
} ?>

<?= $form->field($staffPositionPlace, 'positions')->widget(Select2::class, [
    'data' => Position::getPositionNames(),
    'options' => [
        'id' => 'spp_positions',
        'multiple' => true,
        'prompt' => \Yii::t('app', 'select position'),
    ],
])->label(\Yii::t('app', 'positions')) ?>

<?= Html::button(
    \Yii::t('app', $processButtonTitle),
    [
        'class' => 'js-button-process btn btn-success float-right',
        'autofocus' => 1,
    ]
) ?>

<?= Html::button(
    \Yii::t('app', 'Cancel'),
    [
        'class' => 'js-button-cancel btn btn-secondary float-right mr-3',
        'autofocus' => 1,
    ]
) ?>

<?php UFActiveForm::end() ?>
