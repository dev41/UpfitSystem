<?php

use app\src\entities\coaching\Coaching;
use app\src\entities\coaching\CoachingSearch;
use app\src\helpers\ImageHelper;
use app\src\library\AccessChecker;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use app\src\widget\UFGridView;
use yii\helpers\Url;
use yii\web\View;

/* @var View $this */
/* @var CoachingSearch $searchModel */
/* @var ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Coaching');
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('/_templates/confirm-dialog.php') ?>

<div class="box uf-listing js-listing">

    <div class="box-header">
        <h3 class="box-title"><?= Yii::t('app', 'Coaching List') ?></h3>

        <?php if (AccessChecker::hasPermission('coaching.create')) {
            echo Html::a(Yii::t('app', 'Create Coaching'), ['coaching/create'], ['class' => 'btn btn-sm btn-success float-right']);
        } ?>

    </div>

    <div class="box-body">
        <?= $this->render('/_templates/confirm-dialog.php') ?>
        <div class="coaching-index">

            <?php \yii\widgets\Pjax::begin(['id' => 'coaching-index']); ?>

            <?= UFGridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [

                    'id',
                    [
                        'attribute' => 'name',
                        'sortLinkOptions' => ['class' => 'sorting'],
                    ],
                    [
                        'label' => Yii::t('app', 'Club'),
                        'attribute' => 'clubIds',
                        'value' => 'clubNames',
                        'filter' => $searchModel->getClubsFilterOptions(),
                        'filterInputOptions' => ['prompt' => Yii::t('app', 'selected_all'), 'class' => 'form-control', 'id' => null],
                        'headerOptions' => ['style' => 'min-width: 100px'],
                    ],
                    [
                        'label' => Yii::t('app', 'Place'),
                        'attribute' => 'placeIds',
                        'value' => 'placeNames',
                        'filter' => $searchModel->getPlacesFilterOptions(),
                        'filterInputOptions' => ['prompt' => Yii::t('app', 'selected_all'), 'class' => 'form-control', 'id' => null],
                        'headerOptions' => ['style' => 'min-width: 100px'],
                    ],
                    'level',
                    [
                        'attribute' => 'description',
                        'sortLinkOptions' => ['class' => 'sorting'],
                    ],
                    [
                        'label' => Yii::t('app', 'Trainer'),
                        'attribute' => 'trainerIds',
                        'value' => 'trainerNames',
                        'filter' => $searchModel->getTrainersFilterOptions(),
                        'filterInputOptions' => ['prompt' => Yii::t('app', 'selected_all'), 'class' => 'form-control', 'id' => null],
                        'headerOptions' => ['style' => 'min-width: 100px'],
                        'sortLinkOptions' => ['class' => 'sorting'],
                    ],
                    [
                        'attribute' => 'price',
                        'sortLinkOptions' => ['class' => 'sorting'],
                    ],
                    [
                        'label' => \Yii::t('app', 'Capacity'),
                        'attribute' => 'filterCapacity',
                        'value' => 'capacity',
                    ],
                    [
                        'attribute' => 'image',
                        'format' => 'raw',
                        'value' => function ($model) {
                            if (empty($model['image'])) {
                                return null;
                            }
                            $html = '';
                            $coaching = new Coaching(['id' => $model['id']]);
                            $html = $html . '<img class="img-thumbnail" src="' . ImageHelper::getUrl($coaching, $model['image']) . '"></img>';
                            return Html::a($html);
                        }
                    ],
                [
                    'attribute' => 'color',
                    'format' => 'html',
                    'value' => function($data) {
                        return '<div class="uf-coaching-color-cell" style="background-color: ' . $data['color'] . ';">&nbsp;</div>';
                    },
                    'filter' => false,
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'buttons' => [
                        'delete' => function($url, $model) {
                            if (!AccessChecker::hasPermission('coaching.delete')) {
                                return '';
                            }
                            $title = htmlspecialchars(Yii::t('app', 'Coaching Delete'));
                            $message = htmlspecialchars(Yii::t('app', 'Do you really want to delete "') . $model['name'] . Yii::t('app', '" coaching?'))
                            . ' (' . Yii::t('app', 'All events created from this coaching will be deleted!') . ')';
                            return Html::tag('span', '', [
                                'class' => 'js-delete uf-listing-button uf-listing-button-delete glyphicon glyphicon-trash',
                                'data-url' => $url,
                                'data-title' => $title,
                                'data-confirm-message' => $message,
                                'data-notification-message' => [
                                    'success' => Yii::t('app', 'Coaching have been successfully deleted.'),
                                    'error' => Yii::t('app', 'Coaching have\'t been deleted.'),
                                ],
                            ]);
                        },
                        'update' => function($url, $model) {
                            if (!AccessChecker::hasPermission('coaching.update')) {
                                return '';
                            }
                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url);
                        },
                        'view' => function($url, $model) {
                            return '';
                        }
                    ],
                    'urlCreator' => function ($action, $model) {
                        switch ($action) {
                            case 'update': return Url::to(['coaching/update', 'id' => $model['id']]);
                            case 'delete': return Url::to(['coaching/delete', 'id' => $model['id']]);
                            default: return Url::to(['coaching/view', 'id' => $model['id']]);
                        }
                    }
                ],
            ],
        ]); ?>
    </div>
</div>