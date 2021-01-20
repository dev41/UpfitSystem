<?php

use app\src\entities\access\AccessRole;
use app\src\entities\staff\Staff;
use yii\web\View;

/* @var View $this */
/* @var Staff $staff */
/* @var AccessRole[] $roles */
/* @var array $positions */
/* @var bool $renderAjax */
/* @var array $typeOptions */

$renderAjax = $renderAjax ?? false;

$this->title = $staff->isNewRecord ? \Yii::t('app', 'Staff Create') : \Yii::t('app', 'Staff Update');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Staff'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-lg-8">
        <div class="nav-tabs-form js-nav-tabs <?= $renderAjax ? 'dialog-size' : '' ?>">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a href="#staff-form" data-toggle="tab"
                       aria-expanded="false"><?= $staff->isNewRecord ? \Yii::t('app', 'Staff Create') : \Yii::t('app', 'Staff Update') ?></a>
                </li>
                <li>
                    <a href="#position-list" data-toggle="tab"
                       aria-expanded="false"><?= \Yii::t('app', 'Clubs positions') ?></a>
                </li>
            </ul>

            <div class="tab-content js-edit-staff-container" data-staff_id="<?= $staff->id ?>">
                <div class="tab-pane active" id="staff-form">
                    <?= $this->render('/staff/partial/staff_form', $_params_) ?>
                </div>
                <div class="tab-pane" id="position-list">
                    <?= $this->render('/staff/partial/position-list.php', $_params_) ?>
                </div>
            </div>
            <?= $this->render('/_templates/tooltip.php') ?>
            <?= $this->render('/_templates/confirm-dialog.php') ?>
        </div>
    </div>
</div>