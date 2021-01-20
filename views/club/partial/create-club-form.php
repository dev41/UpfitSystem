<?php

use app\src\entities\place\Club;
use app\src\entities\user\User;
use app\src\widget\UFActiveForm;
use yii\helpers\Html;
use yii\web\View;

/**
 * @var View $this
 * @var Club $club
 * @var User[] $users
 */
?>

<?php $form = UFActiveForm::begin(['id' => 'club-create-form', 'options' => ['class' => 'js-club-update-form']]) ?>

<?= $form->field($club, 'name')->label(\Yii::t('app', 'Name')) ?>

<?= Html::submitButton(\Yii::t('app', 'Create club'), [
    'class' => 'js-button-process btn btn-success float-right',
]) ?>

<?= Html::button(\Yii::t('app', 'Cancel'), [
    'class' => 'js-button-cancel btn btn-default float-right mr-2',
]) ?>

<?php UFActiveForm::end() ?>
