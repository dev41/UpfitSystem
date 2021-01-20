<?php

use app\src\entities\notification\WebNotification;
use app\src\entities\notification\WebNotificationSearch;
use app\src\entities\trigger\Trigger;
use yii\data\ActiveDataProvider;
use kartik\datecontrol\DateControl;
use yii\helpers\Html;
use app\src\widget\UFGridView;
use yii\helpers\Url;
use yii\web\View;

/* @var View $this */
/* @var ActiveDataProvider $dataProvider */
/* @var WebNotificationSearch $searchModel */

$this->title = Yii::t('app', 'Notifications');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="js-notification-container box uf-listing js-listing">

    <div class="box-header">
        <h3 class="box-title"><?= Yii::t('app', 'Notification List') ?></h3>
    </div>

    <div class="box-body">

        <?php \yii\widgets\Pjax::begin(['id' => 'notification-index']); ?>

        <?= $this->render('/_templates/confirm-dialog.php') ?>
        <div class="notification-index">

        <?= UFGridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                [
                    'attribute' => 'id',
                    'label' => Yii::t('app', 'ID'),
                    'filter' => false,
                ],

                [
                    'label' => Yii::t('app', 'Event'),
                    'attribute' => 'event',
                    'value' => function ($model) {
                        return Trigger::EVENT_LABELS[$model['event']];
                    },
                    'filter' => $searchModel->getEventsFilterOptions(),
                    'filterInputOptions' => ['prompt' => Yii::t('app', 'selected_all'), 'class' => 'form-control', 'id' => null],
                ],

                [
                    'attribute' => 'message',
                    'sortLinkOptions' => ['class' => 'sorting'],
                    'label' => Yii::t('app', 'Message'),
                ],

                [
                    'label' => Yii::t('app', 'Status'),
                    'attribute' => 'status',
                    'format' => 'raw',
                    'contentOptions' => ['class' => 'text-center'],
                    'value' => function ($model) {
                        $statusClasses = (int) $model['status'] === WebNotification::STATUS_ALREADY_READ ?
                            'fa-envelope-o' : 'fa-envelope text-yellow';
                        $jsClass = 'js-set-status';
                        return Html::tag('span', '', [
                            'class' => $jsClass . ' uf-notification-change-status fa ' . $statusClasses,
                            'data-url' => Url::to([
                                'notification/set-status', 'userId' => $model['user_id'], 'notificationId' => $model['id']
                            ])
                        ]);
                    },
                    'filter' => $searchModel->getStatusFilterOptions(),
                    'filterInputOptions' => ['prompt' => Yii::t('app', 'selected_all'), 'class' => 'form-control', 'id' => null],
                    'sortLinkOptions' => ['class' => 'sorting'],
                ],

                [
                    'attribute' => 'created_at',
                    'filter' => DateControl::widget([
                        'model' => $searchModel,
                        'type' => DateControl::FORMAT_DATE,
                        'attribute' => 'created_at',
                        'options' => ['placeholder' => 'Select created_at date'],
                    ]),
                    'format' => 'html',
                    'label' => Yii::t('app', 'Created At'),
                ],

                [
                    'class' => 'yii\grid\ActionColumn',
                    'buttons' => [

                        'update' => function ($url, $model) {
                            return '';
                        },
                        'delete' => function ($url, $model) {

                            $title = htmlspecialchars(Yii::t('app', 'Notification Delete'));
                            $message = htmlspecialchars(Yii::t('app', 'Do you really want to delete the notification?'));
                            return Html::tag('span', '', [
                                'class' => 'js-delete-notification uf-listing-button uf-listing-button-delete glyphicon glyphicon-trash',
                                'data-url' => $url,
                                'data-title' => $title,
                                'data-confirm-message' => $message,
                                'data-notification-message' => [
                                    'success' => Yii::t('app', 'Notification have been successfully deleted.'),
                                    'error' => Yii::t('app', 'Notification have\'t been deleted.'),
                                ],
                            ]);
                        },
                    ],
                    'urlCreator' => function ($action, $model) {
                        switch ($action) {
                            case 'update': return Url::to(['notification/update', 'id' => $model['id']]);
                            case 'delete': return Url::to(['notification/delete', 'id' => $model['id']]);
                            default: return Url::to(['notification/view', 'id' => $model['id']]);
                        }
                    }
                ],
            ],
        ]); ?>

        <?php \yii\widgets\Pjax::end(); ?>
    </div>
</div>
