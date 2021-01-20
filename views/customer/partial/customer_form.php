<?php

use app\src\entities\customer\Customer;
use app\src\entities\customer\CustomerPlace;
use app\src\widget\UFActiveForm;
use kartik\datecontrol\DateControl;
use yii\helpers\Html;
use yii\web\View;

/* @var View $this */
/* @var Customer $customer */
/* @var CustomerPlace $customerPlace */

$renderAjax = $renderAjax ?? false;
?>

<?php $form = UFActiveForm::begin(['id' => 'customer-update-form']) ?>

<?php if ($renderAjax) {
    echo $form->field($customerPlace, 'place_id', [
        'template' => '{input}',
        'options' => ['class' => 'js-club_id'],
    ])->hiddenInput()->label(false);
} ?>

<?= $form->hiddenInput($customer, 'id') ?>
<?php if ($renderAjax) {
    echo $form->hiddenInput($customerPlace, 'user_id');
} ?>

<?= $form->field($customer, 'username')->label(\Yii::t('app', 'Username')) ?>

<?= $form->field($customer, 'first_name')->label(\Yii::t('app', 'First Name')) ?>

<?= $form->field($customer, 'last_name')->label(\Yii::t('app', 'Last Name')) ?>

<?= $form->field($customer, 'email')->label(\Yii::t('app', 'Email')) ?>

<?php if ($renderAjax) {
    echo $form->field($customerPlace, 'card_number')->label(\Yii::t('app', 'Card Number'));
} ?>

<?= $form->field($customer, 'phone')->label(\Yii::t('app', 'Phone')) ?>

<?= $form->field($customer, 'birthday')->label(\Yii::t('app', 'Birthday'))->widget(DateControl::class, [
    'type' => DateControl::FORMAT_DATE,
    'options' => ['placeholder' => 'Select created_at date'],
    'autoWidget' => true,
]) ?>

<?= $form->field($customer, 'description')->label(\Yii::t('app', 'Description')) ?>

<?php if ($customer->isNewRecord): ?>
    <?= $form->field($customer, 'password')->passwordInput()->label(\Yii::t('app', 'Password')) ?>
    <?= $form->field($customer, 'confirm_password')->passwordInput()->label(\Yii::t('app', 'Confirm Password')) ?>
<?php endif; ?>

<?= Html::submitButton(
    $customer->isNewRecord ? \Yii::t('app', 'Add Customer')
        : \Yii::t('app', 'Update Customer'), [
    'class' => 'js-button-process btn btn-success float-right',
]) ?>

<?php if ($renderAjax) {
    echo Html::button(
        \Yii::t('app', 'Cancel'),
        [
            'class' => 'js-button-cancel btn btn-secondary float-right mr-3',
            'autofocus' => 1,
        ]
    );
} ?>

<?php UFActiveForm::end() ?>