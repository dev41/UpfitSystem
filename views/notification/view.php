<?php

use app\src\entities\notification\WebNotification;
use app\src\entities\trigger\Trigger;
use yii\helpers\Html;
use yii\web\View;

/* @var View $this */
/* @var WebNotification $notification */

$this->title = Yii::t('app', 'Notification View');
$this->params['breadcrumbs'][] = ['label' => \Yii::t('app', 'Notification'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-10">
            <div class="box uf-view js-view">

                <div class="box-header">
                    <h3 class="box-title"><?= Trigger::EVENT_LABELS[$notification->event] ?></h3>
                </div>

                <div class="box-body">

                    <?= $this->render('/_templates/confirm-dialog.php') ?>
                    <div class="row">
                        <div class="col-sm-3 col-md-2"><strong><?= Yii::t('app', 'Message') ?></strong></div>
                        <div class="col-sm-9 col-md-10"><?= $notification->message ?></div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3 col-md-2"><strong><?= Yii::t('app', 'Date') ?></strong></div>
                        <div class="col-sm-9 col-md-10"><?= $notification->created_at ?></div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12">

                            <?= Html::a(\Yii::t('app', 'Back'), ['/notification/index'],
                                [
                                    'class' => 'btn btn-success float-left mr-3',
                                    'value' => 'save',
                                ]
                            ) ?>

                            <?php
                            $title = htmlspecialchars(Yii::t('app', 'Delete notification'));
                            $message = htmlspecialchars(Yii::t('app', 'Do you really want to delete the notification?'));
                            echo Html::tag('span', \Yii::t('app', 'Delete'), [
                                'class' => 'js-delete uf-view-button uf-view-button-delete btn btn-danger float-right',
                                'data-url' => '/notification/delete?id=' . $notification->id,
                                'data-title' => $title,
                                'data-message' => $message,
                            ]); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>