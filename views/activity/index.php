<?php

use app\src\entities\activity\Activity;
use app\src\entities\activity\ActivitySearch;
use app\src\helpers\ImageHelper;
use app\src\library\AccessChecker;
use kartik\datecontrol\DateControl;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use app\src\widget\UFGridView;
use yii\helpers\Url;
use yii\web\View;
use app\src\widget\UFDateRangePicker;

/* @var View $this */
/* @var ActivitySearch $searchModel */
/* @var ActiveDataProvider $dataProvider */

$this->title = \Yii::t('app', 'Activity');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="box uf-listing js-listing">

    <div class="box-header">
        <h3 class="box-title"><?= \Yii::t('app', 'Activity List') ?></h3>

        <?php if (AccessChecker::hasPermission('activity.create')) {
            echo Html::a(\Yii::t('app', 'Create Activity'), ['activity/create'], ['class' => 'btn btn-sm btn-success float-right']);
        } ?>

    </div>

    <div class="box-body">

        <?php \yii\widgets\Pjax::begin(['id' => 'activity-index']); ?>

        <?= $this->render('/_templates/confirm-dialog.php') ?>
        <div class="activity-index">

        <?= UFGridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                [
                    'attribute' => 'id',
                    'filter' => false,
                ],
                [
                    'attribute' => 'name',
                    'sortLinkOptions' => ['class' => 'sorting'],
                ],
                [
                    'attribute' => 'images',
                    'format' => 'raw',
                    'value' => function ($model) {
                        if (empty($model['images'])) {
                            return null;
                        }
                        $html = '';
                        $activity = new Activity(['id' => $model['id']]);
                        $html = $html . '<img class="img-thumbnail" src="' . ImageHelper::getUrl($activity, $model['images']) . '"></img>';
                        return Html::a($html);
                    }
                ],
                [
                    'attribute' => 'description',
                    'sortLinkOptions' => ['class' => 'sorting'],
                    'format' => 'html',
                ],
                [
                    'attribute' => 'price',
                    'sortLinkOptions' => ['class' => 'sorting'],
                ],
                [
                    'attribute' => 'capacity',
                    'sortLinkOptions' => ['class' => 'sorting'],
                ],
                [
                    'label' => \Yii::t('app', "Start"),
                    'attribute' => 'start',
                    'value' => 'start',
                    'format' => 'html',
                    'filter' => UFDateRangePicker::widget([
                        'id' => 'date-range-start',
                        'model' => $searchModel,
                        'attribute' => 'startTimeRange',
                    ]),
                ],
                [
                    'attribute' => 'created_at',
                    'filter' => DateControl::widget([
                        'model' => $searchModel,
                        'type' => DateControl::FORMAT_DATE,
                        'attribute' => 'created_at',
                        'options' => ['placeholder' => 'Select created_at date'],
                    ]),
                    'headerOptions' => ['style' => 'min-width: 170px'],
                    'format' => 'html',
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'buttons' => [
                        'view' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url);
                        },
                        'update' => function ($url, $model) {
                            if (!AccessChecker::hasPermission('activity.update')) {
                                return '';
                            }
                            $title = htmlspecialchars(\Yii::t('app', 'Update "') . $model['name'] . '"');
                            return Html::a('<span class="glyphicon glyphicon-pencil"
                                data-title="' . $title . '"></span>', $url);
                        },
                        'delete' => function ($url, $model) {
                            if (!AccessChecker::hasPermission('activity.delete')) {
                                return '';
                            }
                            $title = htmlspecialchars(\Yii::t('app', 'Activity Delete'));
                            $message = htmlspecialchars(
                                    \Yii::t('app', 'Do you really want to delete "') . $model['name'] . \Yii::t('app', '" activity') . '?'
                            );

                            return Html::tag('span', '', [
                                'class' => 'js-delete uf-listing-button uf-listing-button-delete glyphicon glyphicon-trash',
                                'data-url' => $url,
                                'data-title' => $title,
                                'data-confirm-message' => $message,
                                'data-notification-message' => [
                                    'success' => \Yii::t('app', 'Activity have been successfully deleted.'),
                                    'error' => \Yii::t('app', 'Activity have\'t been deleted.'),
                                ],
                            ]);
                        },
                    ],
                    'urlCreator' => function ($action, $model) {
                        switch ($action) {
                            case 'view': return Url::to(['activity/view', 'id' => $model['id']]);
                            case 'update': return Url::to(['activity/update', 'id' => $model['id']]);
                            case 'delete': return Url::to(['activity/delete', 'id' => $model['id']]);
                            default: return Url::to(['activity/view', 'id' => $model['id']]);
                        }
                    }
                ],
            ],
        ]); ?>
        </div>
        <?php \yii\widgets\Pjax::end(); ?>
    </div>
</div>
