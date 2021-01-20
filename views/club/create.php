<?php

use app\src\entities\place\Club;
use app\src\entities\user\User;
use app\src\entities\translate\Language;
use yii\web\View;

/* @var View $this */
/* @var Club $club */
/* @var User[] $owners */

$this->title = \Yii::t('app', 'Club Update');
$this->params['breadcrumbs'][] = ['label' => \Yii::t('app', 'Clubs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="nav-tabs-form js-nav-tabs">
    <ul class="nav nav-tabs">
        <li class="active">
            <a href="#club-update-info" data-toggle="tab" aria-expanded="false"><?= \Yii::t('app', 'Info') ?></a>
        </li>
        <li>
            <a href="#club-update-contacts" data-toggle="tab"
               aria-expanded="false"><?= \Yii::t('app', 'Contacts') ?></a>
        </li>
        <li>
            <a href="#staff" data-toggle="tab" aria-expanded="false"><?= \Yii::t('app', 'Staff') ?></a>
        </li>
        <li>
            <a href="#attributes" data-toggle="tab" aria-expanded="false"><?= \Yii::t('app', 'Attributes') ?></a>
        </li>
        <li>
            <a href="#customers" data-toggle="tab" aria-expanded="false"><?= \Yii::t('app', 'Customers') ?></a>
        </li>
        <li>
            <a href="#payment" data-toggle="tab" aria-expanded="false"><?= \Yii::t('app', 'Payment') ?></a>
        </li>
        <li>
            <a href="#translations" data-toggle="tab" aria-expanded="false"><?= \Yii::t('app', 'Translations') ?></a>
        </li>
    </ul>

    <div class="tab-content js-edit-club-container" data-club_id="<?= $club->id ?>">
<!--        <div class="container-fluid">-->
            <div class="tab-pane active" id="club-update-info">
                <?= $this->render('/club/partial/main-form.php', $_params_) ?>
            </div>
<!--        </div>-->
        <div class="tab-pane" id="club-update-contacts">
            <?= $this->render('/club/partial/contacts-form.php', $_params_) ?>
        </div>
        <div class="tab-pane" id="staff">
            <?= $this->render('/club/partial/staff-list.php', $_params_ + ['clubId' => $club->id]) ?>
        </div>
        <div class="tab-pane" id="attributes">
            <?= $this->render('/club/partial/attributes-list.php', $_params_) ?>
        </div>
        <div class="tab-pane" id="customers">
            <?= $this->render('/club/partial/customers-list.php', $_params_) ?>
        </div>
        <div class="tab-pane" id="payment">
            <?= $this->render('/club/partial/payment.php', $_params_) ?>
        </div>
        <div class="tab-pane" id="translations">
            <?= $this->render('/club/partial/_translations', [
                'model' => $club,
                'languages' => Language::getList(),
            ]) ?>
        </div>
    </div>
    <?= $this->render('/_templates/confirm-dialog.php') ?>
</div>