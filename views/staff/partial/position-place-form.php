<?php

use app\src\entities\place\Club;
use app\src\entities\staff\StaffPositionPlace;
use app\src\entities\user\Position;
use app\src\widget\UFActiveForm;
use yii\helpers\ArrayHelper;
use yii\web\View;
use kartik\select2\Select2;
use yii\helpers\Html;

/**
 * @var View $this
 * @var string $processButtonTitle
 * @var StaffPositionPlace $staffPositionPlace
 * @var array $availableStaff
 * @var bool $renderAjax
 * @var Club[] $clubs
 */

$processButtonTitle = $processButtonTitle ?? $staffPositionPlace->isNewRecord
        ? Yii::t('app', 'Add Position')
        : Yii::t('app', 'Update Position');
$renderAjax = $renderAjax ?? false;
$form = UFActiveForm::begin() ?>

<div class="<?= $renderAjax ? 'dialog-size' : '' ?>">
    <?php
    if ($staffPositionPlace->isNewRecord) {
        echo $form->field($staffPositionPlace, 'place_id')->widget(Select2::class, [
            'data' => ArrayHelper::map($clubs, 'id', 'name'),
            'options' => [
                'id' => 'spp_user_id',
                'multiple' => false,
                'prompt' => \Yii::t('app', 'select name'),
            ],
        ])->label(\Yii::t('app', 'Club'));
    } else {
        echo $form->field($staffPositionPlace, 'place_id', ['template' => '{input}'])->hiddenInput()->label(false);
    }
    ?>

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
</div>
