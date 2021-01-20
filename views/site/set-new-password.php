<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\View;

/* @var View $this */
/* @var ActiveForm $form */
/* @var string $token */
?>

<div class="login-box">
    <div class="login-logo">
        <a href="#"><b>Up</b>Fit</a>
    </div>

    <div class="login-box-body">
        <p class="login-box-msg"><?= Yii::t('app', 'Password recovery')?></p>

        <?php $form = ActiveForm::begin(['action' => Url::to(['/site/set-new-password'])]); ?>

        <?= Html::hiddenInput('t', $token); ?>

        <div class="form-group has-feedback">
            <?= Html::passwordInput('password', '',
                [
                    'placeholder' => Yii::t('app', 'New password'),
                    'class' => 'form-control',
                    'required' => true,
                ]
            ) ?>
            <span class='glyphicon glyphicon-lock form-control-feedback'></span>
        </div>

        <div>
            <?= Html::submitButton(Yii::t('app', 'Set new password'), ['class' => 'btn btn-primary btn-block btn-flat']) ?>
        </div>

        <?php ActiveForm::end(); ?>

        <br>
        <a href="<?= Url::to(['/site/login']) ?>"><?= Yii::t('app', 'Back to login form')?></a><br>
    </div>

    <!-- /.login-box-body -->
</div><!-- /.login-box -->