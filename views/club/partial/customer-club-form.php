<?php

use app\src\entities\customer\CustomerPlace;
use app\src\widget\UFActiveForm;
use yii\web\View;
use kartik\select2\Select2;
use yii\helpers\Html;
/**
 * @var View $this
 * @var string $processButtonTitle
 * @var CustomerPlace $customerPlace
 * @var array $availableCustomers
 */
$processButtonTitle = $processButtonTitle ?? ($customerPlace->isNewRecord ?
        \Yii::t('app', 'Add Customer') :
        \Yii::t('app', 'Update Customer'));

$form = UFActiveForm::begin(['id' => 'customer-club-form']) ?>

<?= $form->field($customerPlace, 'users')->widget(Select2::class, [
    'data' => $availableCustomers,
    'options' => [
        'id' => 'customer_user_id',
        'multiple' => true,
        'prompt' => \Yii::t('app', 'select name'),
    ],
])->label(\Yii::t('app', 'Username')) ?>

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