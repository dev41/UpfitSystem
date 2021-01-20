<?php

use app\src\entities\activity\Activity;
use app\src\entities\user\User;
use app\src\library\AccessChecker;
use yii\helpers\Html;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use \app\src\helpers\ImageHelper;
use yii\web\View;

/* @var View $this */
/* @var Activity $activity */
/* @var User[] $organizers */

$this->title = Yii::t('app', 'Activity View');
$this->params['breadcrumbs'][] = ['label' => 'Activity', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$image = $activity->getImage();
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-10">
            <div class="box uf-view js-view">

                <div class="box-header">
                    <h3 class="box-title"><?= $activity->name ?></h3>
                </div>

                <div class="box-body">

                    <?= $this->render('/_templates/confirm-dialog.php') ?>

                    <div class="row">
                        <div class="col-sm-3 col-md-2"><strong><?= Yii::t('app', 'Club') ?></strong></div>
                        <div class="col-sm-9 col-md-10"><?= $activity->getClub()->name ?></div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3 col-md-2"><strong><?= Yii::t('app', 'Logo') ?></strong></div>
                        <div class="col-sm-9 col-md-10">
                            <?php if ($image['file_name']): ?>
                                <img class="img-thumbnail" src="<?= ImageHelper::getUrl($activity, $image['file_name']) ?>">
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3 col-md-2"><strong><?= Yii::t('app', 'Description') ?></strong></div>
                        <div class="col-sm-9 col-md-10"><?= $activity->description ?></div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3 col-md-2"><strong><?= Yii::t('app', 'Price') ?></strong></div>
                        <div class="col-sm-9 col-md-10"><?= $activity->price ?></div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3 col-md-2"><strong><?= Yii::t('app', 'Capacity') ?></strong></div>
                        <div class="col-sm-9 col-md-10"><?= $activity->capacity ?></div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3 col-md-2"><strong><?= Yii::t('app', 'Start') ?></strong></div>
                        <div class="col-sm-9 col-md-10"><?= ($activity->start) ? date('F dS Y, h:m a', strtotime($activity->start)) : '' ?></div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3 col-md-2"><strong><?= Yii::t('app', 'End') ?></strong></div>
                        <div class="col-sm-9 col-md-10"><?= ($activity->end) ? date('F dS Y, h:m a', strtotime($activity->end)) : '' ?></div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3 col-md-2"><strong><?= Yii::t('app', 'Created Date') ?></strong></div>
                        <div class="col-sm-9 col-md-10"><?= $activity->created_at ?></div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3 col-md-2"><strong><?= Yii::t('app', 'Organizers') ?></strong></div>
                        <div class="col-sm-9 col-md-10">
                            <?= Select2::widget([
                                'name' => 'users',
                                'data' => ArrayHelper::map($organizers, 'id', 'username'),
                                'value' => ArrayHelper::map($organizers, 'id', 'id'),
                                'options' => [
                                    'disabled' => true,
                                    'id' => 'activity_organizers_ids',
                                    'multiple' => true,
                                    'prompt' => '',
                                ],
                            ]); ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12">

                            <?= Html::a(\Yii::t('app', 'Back'), ['/activity/index'],
                                [
                                    'class' => 'btn btn-success float-left mr-3',
                                    'value' => 'save',
                                ]
                            ) ?>

                            <?php if (AccessChecker::hasPermission('activity.update')) {
                                echo Html::a(\Yii::t('app', 'Edit'), ['/activity/update?id=' . $activity->id],
                                    [
                                        'class' => 'btn btn-success float-left',
                                        'value' => 'save',
                                    ]
                                );
                            } ?>

                            <?php if (AccessChecker::hasPermission('activity.delete')) {
                                $title = htmlspecialchars(Yii::t('app', 'Delete activity'));
                                $message = htmlspecialchars(Yii::t('app', 'Do you really want to delete the activity?'));
                                echo Html::tag('span', Yii::t('app', 'Delete'), [
                                    'class' => 'js-delete uf-view-button uf-view-button-delete btn btn-danger float-right',
                                    'data-url' => '/activity/delete?id=' . $activity->id,
                                    'data-title' => $title,
                                    'data-message' => $message,
                                ]);
                            } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>