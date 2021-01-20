<?php

use app\src\entities\place\Club;
use app\src\widget\UFActiveForm;
use yii\helpers\Html;
use yii\web\View;
use \app\src\helpers\InputHelper;

/* @var View $this */
/* @var Club $club */
?>

<div class="uf-form-create">

    <div class="box-body">
        <div class="row">
            <div class="col-lg-6">
                <?php $form = UFActiveForm::begin(['id' => 'club-update-payment-form', 'options' => ['class' => 'js-club-update-payment-form']]) ?>

                <?= $form->field($club, 'public_key')->input('text', ['autocomplete' => 'off'])->label(\Yii::t('app', 'Public key')) ?>
                <?= $form->textInput($club, 'private_key', ['class' => 'form-control', 'type' => 'password', 'autocomplete' => 'off'], InputHelper::TYPE_PASSWORD) ?>

                <div class="box-footer">
                    <?= Html::submitButton(\Yii::t('app', 'Update Payment'), [
                        'class' => 'js-button-process-contacts btn btn-success float-right',
                        'data-url' => 'update-info',
                        'data-form-name' => '.js-club-update-payment-form',
                        'data-message' => [
                            'success' => Yii::t('app', 'Club payment data was saved.'),
                            'error' => Yii::t('app', 'Club payment data was\'t save!'),
                        ]
                    ]) ?>
                </div>

                <?php UFActiveForm::end() ?>

            </div>
        </div>
    </div>
</div>