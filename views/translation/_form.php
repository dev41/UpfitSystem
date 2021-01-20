<?php

use app\src\entities\translate\Translation;
use app\src\entities\translate\Language;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model Translation */
/* @var $form yii\widgets\ActiveForm */
?>


<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'language')->dropdownList(Language::getList(), ['disabled' => !$model->isNewRecord]) ?>

<?= $form->field($model->message, 'message')->textarea(['maxlength' => true, 'disabled' => !$model->isNewRecord, 'rows' => 3]) ?>

<?= $form->field($model, 'translation')->textarea(['maxlength' => true, 'rows' => 3]) ?>

<?= Html::a(Yii::t('app', 'Cancel'), ['index'], ['class' => 'btn btn-primary']) ?>

<?= Html::submitButton(
    $model->isNewRecord ? \Yii::t('app', 'Create Translation')
        : \Yii::t('app', 'Update Translation'),
    [
        'class' => 'js-button-process btn btn-success float-right',
        'autofocus' => 1,
    ]
) ?>

<?php ActiveForm::end(); ?>


