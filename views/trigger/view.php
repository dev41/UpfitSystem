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
/* @var Trigger $trigger */
/* @var User[] $receivers */

$this->title = Yii::t('app', 'Trigger View');
$this->params['breadcrumbs'][] = ['label' => \Yii::t('app', 'Trigger'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$receivers = $receivers ?? [];
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <div class="box uf-view js-view">

                <div class="box-header">
                    <h3 class="box-title"><?= $trigger->name ?></h3>>
                </div>

                <div class="box-body">

                    <?= $this->render('/_templates/confirm-dialog.php') ?>
                    <div class="row">
                        <div class="col-sm-3 col-md-2"><strong><?= Yii::t('app', 'Types') ?></strong></div>
                        <div class="col-sm-9 col-md-10"><?= TriggerHelper::getTypeLabels($trigger['types']); ?></div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3 col-md-2"><strong><?= Yii::t('app', 'Event') ?></strong></div>
                        <div class="col-sm-9 col-md-10"><?= Trigger::EVENT_LABELS[$trigger->event] ?></div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3 col-md-2"><strong><?= Yii::t('app', 'Clubs') ?></strong></div>
                        <div class="col-sm-9 col-md-10">
                                <?= Select2::widget([
                                    'name' => 'clubs',
                                    'data' => ArrayHelper::map($trigger->getPlaces(), 'id', 'name'),
                                    'value' => ArrayHelper::map($trigger->getPlaces(), 'id', 'id'),
                                    'options' => [
                                        'disabled' => true,
                                        'id' => 'trigger_club_ids',
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
                                    'data' => $trigger->to_user_type,
                                    'value' => array_keys($trigger->to_user_type),
                                    'options' => [
                                        'disabled' => true,
                                        'id' => 'trigger_user_type',
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
                                    'data' => ArrayHelper::map($trigger->getPositions(), 'id', 'name'),
                                    'value' => ArrayHelper::map($trigger->getPositions(), 'id', 'id'),
                                    'options' => [
                                        'disabled' => true,
                                        'id' => 'trigger_position_ids',
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
                                    'data' => ArrayHelper::map($trigger->getRoles(), 'id', 'name'),
                                    'value' => ArrayHelper::map($trigger->getRoles(), 'id', 'id'),
                                    'options' => [
                                        'disabled' => true,
                                        'id' => 'trigger_role_ids',
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
                                    'data' => ArrayHelper::map($trigger->getUsers(), 'id', 'username'),
                                    'value' => ArrayHelper::map($trigger->getUsers(), 'id', 'id'),
                                    'options' => [
                                        'disabled' => true,
                                        'id' => 'trigger_user_ids',
                                        'multiple' => true,
                                        'prompt' => '',
                                    ],
                                ]); ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3 col-md-2"><strong><?= Yii::t('app', 'Template') ?></strong></div>
                        <div class="col-sm-9 col-md-10"><?= $trigger->template ?></div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3 col-md-2"><strong><?= Yii::t('app', 'Receivers') ?></strong></div>
                        <div class="col-sm-9 col-md-10"><?= $this->render('/trigger/partial/receivers.php', $_params_) ?></div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12">
                            <?= Html::a(\Yii::t('app', 'Back'), ['/trigger/index'],
                                [
                                    'class' => 'btn btn-success float-left mr-3',
                                    'value' => 'save',
                                ]
                            ) ?>

                            <?php if (AccessChecker::hasPermission('trigger.update')) {
                                echo Html::a(\Yii::t('app', 'Edit'), ['/trigger/update?id=' . $trigger->id],
                                    [
                                        'class' => 'btn btn-success float-left',
                                        'value' => 'save',
                                    ]
                                );} ?>

                            <?php if (AccessChecker::hasPermission('trigger.delete')) {
                                $title = htmlspecialchars(Yii::t('app', 'Delete club'));
                                $message = htmlspecialchars(Yii::t('app', 'Do you really want to delete "') . $trigger['name'] . Yii::t('app', '" trigger?'));
                                echo Html::tag('span', \Yii::t('app', 'Delete'), [
                                    'class' => 'js-delete uf-view-button uf-view-button-delete btn btn-danger float-right',
                                    'data-url' => '/trigger/delete?id=' . $trigger->id,
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