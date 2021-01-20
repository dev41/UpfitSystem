<?php

use yii\helpers\Url;
use yii\web\View;

/* @var View $this */
?>

<div class="login-box">
    <div class="login-logo">
        <a href="#"><b>Up</b>Fit</a>
    </div>

    <div class="login-box-body">
        <p class="login-box-msg"><?= Yii::t('app', 'The link has expired or broken.')?></p>

        <br>
        <a href="<?= Url::to(['/site/login']) ?>"><?= Yii::t('app', 'Back to login form')?></a><br>
    </div>

</div>