<?php

use app\src\form\LoginForm;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\View;

/* @var View $this */
/* @var ActiveForm $form */
/* @var LoginForm $model */

$this->title = 'Sign In';

$fieldOptions1 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-envelope form-control-feedback'></span>"
];

$fieldOptions2 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-lock form-control-feedback'></span>"
];
?>

<div class="login-box">
    <div class="login-logo">
        <a href="#"><b>Up</b>Fit</a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body js-login-form-box">
        <p class="login-box-msg"><?= Yii::t('app', 'Sign in to start your session')?></p>

        <?php $form = ActiveForm::begin(['id' => 'login-form', 'enableClientValidation' => false]); ?>

        <?= $form
            ->field($model, 'username', $fieldOptions1)
            ->label(false)
            ->textInput(['placeholder' => Yii::t('app', 'Username or email')]) ?>

        <?= $form
            ->field($model, 'password', $fieldOptions2)
            ->label(false)
            ->passwordInput(['placeholder' => Yii::t('app', 'Password')]) ?>

        <div class="row">
            <div class="col-xs-8">
                <?= $form->field($model, 'rememberMe')->checkbox(['class' => null])->label(Yii::t('app', 'Remember Me')) ?>
            </div>
            <!-- /.col -->
            <div class="col-xs-4">
                <?= Html::submitButton(Yii::t('app', 'Sign in'), ['class' => 'btn btn-primary btn-block btn-flat', 'name' => 'login-button']) ?>
            </div>
            <!-- /.col -->
        </div>


        <?php ActiveForm::end(); ?>


        <!-- /.social-auth-links -->

        <a href="#" class="js-forgot-pass-btn"><?= Yii::t('app', 'I forgot my password')?></a><br>
        <a href="register.html" style="display: none" class="text-center"><?= Yii::t('app', 'Register a new membership')?></a>

    </div>

    <div class="login-box-body js-restore-pass-box" style="display: none">
        <p class="login-box-msg"><?= Yii::t('app', 'Password recovery')?></p>

        <?php $form = ActiveForm::begin(['action' => Url::to(['/site/send-reset-password-link'])]); ?>

        <div class="form-group has-feedback">
            <?= Html::textInput('user_identifier', '',
                [
                    'placeholder' => Yii::t('app', 'Username or email'),
                    'class' => 'form-control',
                    'required' => true,
                ]
            ) ?>
            <span class='glyphicon glyphicon-envelope form-control-feedback'></span>
        </div>

        <div>
            <?= Html::submitButton(Yii::t('app', 'Restore'), ['class' => 'btn btn-primary btn-block btn-flat']) ?>
        </div>

        <?php ActiveForm::end(); ?>

        <br>
        <a href="#" class="js-back-to-login-btn"><?= Yii::t('app', 'Back to login form')?></a><br>
    </div>

    <!-- /.login-box-body -->
</div><!-- /.login-box -->