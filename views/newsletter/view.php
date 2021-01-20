<?php

use app\src\entities\trigger\Trigger;
use app\src\entities\user\User;
use app\src\helpers\TriggerHelper;
use app\src\library\AccessChecker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;

/* @var View $this */
/* @var Trigger $newsletter */
/* @var User[] $receivers */

$this->title = Yii::t('app', 'Newsletter View');
$this->params['breadcrumbs'][] = ['label' => \Yii::t('app', 'Newsletter'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$receivers = $receivers ?? [];
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <div class="box uf-view js-view">

                <div class="box-header">
                    <h3 class="box-title"><?= $newsletter->name ?></h3>>
                </div>

                <div class="box-body">

                    <?= $this->render('/_templates/confirm-dialog.php') ?>
                    <div class="row">
                        <div class="col-sm-3 col-md-2"><strong><?= Yii::t('app', 'Types') ?></strong></div>
                        <div class="col-sm-9 col-md-10"><?= TriggerHelper::getTypeLabels($newsletter['types']); ?></div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3 col-md-2"><strong><?= Yii::t('app', 'Clubs') ?></strong></div>
                        <div class="col-sm-9 col-md-10">
                                <?= Select2::widget([
                                    'name' => 'clubs',
                                    'data' => ArrayHelper::map($newsletter->getPlaces(), 'id', 'name'),
                                    'value' => ArrayHelper::map($newsletter->getPlaces(), 'id', 'id'),
                                    'options' => [
                                        'disabled' => true,
                                        'id' => 'newsletter_club_ids',
                                        'multiple' => true,
                                        'prompt' => '',
                                    ],
                                ]);?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3 col-md-2"><strong><?= Yii::t('app', 'User Type') ?></strong></div>
                        <div class="col-sm-9 col-md-10">
                                <?= Select2::widget([
                                    'name' => 'user type',
                                    'data' => $newsletter->to_user_type,
                                    'value' => array_keys($newsletter->to_user_type),
                                    'options' => [
                                        'disabled' => true,
                                        'id' => 'newsletter_user_type',
                                        'multiple' => true,
                                        'prompt' => '',
                                    ],
                                ]);?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3 col-md-2"><strong><?= Yii::t('app', 'Positions') ?></strong></div>
                        <div class="col-sm-9 col-md-10">
                                <?= Select2::widget([
                                    'name' => 'positions',
                                    'data' => ArrayHelper::map($newsletter->getPositions(), 'id', 'name'),
                                    'value' => ArrayHelper::map($newsletter->getPositions(), 'id', 'id'),
                                    'options' => [
                                        'disabled' => true,
                                        'id' => 'newsletter_position_ids',
                                        'multiple' => true,
                                        'prompt' => '',
                                    ],
                                ]); ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3 col-md-2"><strong><?= Yii::t('app', 'Roles') ?></strong></div>
                        <div class="col-sm-9 col-md-10">
                                <?= Select2::widget([
                                    'name' => 'roles',
                                    'data' => ArrayHelper::map($newsletter->getRoles(), 'id', 'name'),
                                    'value' => ArrayHelper::map($newsletter->getRoles(), 'id', 'id'),
                                    'options' => [
                                        'disabled' => true,
                                        'id' => 'newsletter_role_ids',
                                        'multiple' => true,
                                        'prompt' => '',
                                    ],
                                ]); ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3 col-md-2"><strong><?= Yii::t('app', 'Users') ?></strong></div>
                        <div class="col-sm-9 col-md-10">
                                <?= Select2::widget([
                                    'name' => 'users',
                                    'data' => ArrayHelper::map($newsletter->getUsers(), 'id', 'username'),
                                    'value' => ArrayHelper::map($newsletter->getUsers(), 'id', 'id'),
                                    'options' => [
                                        'disabled' => true,
                                        'id' => 'newsletter_user_ids',
                                        'multiple' => true,
                                        'prompt' => '',
                                    ],
                                ]); ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3 col-md-2"><strong><?= Yii::t('app', 'Template') ?></strong></div>
                        <div class="col-sm-9 col-md-10"><?= $newsletter->template ?></div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3 col-md-2"><strong><?= Yii::t('app', 'Receivers') ?></strong></div>
                        <div class="col-sm-9 col-md-10"><?= $this->render('/newsletter/partial/receivers.php', $_params_) ?></div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12">
                            <?= Html::a(\Yii::t('app', 'Back'), ['/newsletter/index'],
                                [
                                    'class' => 'btn btn-success float-left mr-3',
                                    'value' => 'save',
                                ]
                            ) ?>

                            <?php if (AccessChecker::hasPermission('newsletter.update')) {
                                echo Html::a(\Yii::t('app', 'Edit'), ['/newsletter/update?id=' . $newsletter->id],
                                    [
                                        'class' => 'btn btn-success float-left',
                                        'value' => 'save',
                                    ]
                                );} ?>

                            <?php if (AccessChecker::hasPermission('newsletter.delete')) {
                                $title = htmlspecialchars(Yii::t('app', 'Delete club'));
                                $message = htmlspecialchars(Yii::t('app', 'Do you really want to delete "') . $newsletter['name'] . Yii::t('app', '" newsletter?'));
                                echo Html::tag('span', \Yii::t('app', 'Delete'), [
                                    'class' => 'js-delete uf-view-button uf-view-button-delete btn btn-danger float-right',
                                    'data-url' => '/newsletter/delete?id=' . $newsletter->id,
                                    'data-title' => $title,
                                    'data-message' => $message,
                                ]);}?>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>