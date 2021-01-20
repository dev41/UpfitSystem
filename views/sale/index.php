<?php

use app\src\entities\sale\Sale;
use app\src\entities\sale\SaleSearch;
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
/* @var SaleSearch $searchModel  */
/* @var ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Sale');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="box uf-listing js-listing">

    <div class="box-header">
        <h3 class="box-title"><?= Yii::t('app', 'Sale List') ?></h3>

        <?php if (AccessChecker::hasPermission('sale.create')) {
            echo Html::a(Yii::t('app', 'Create Sale'), ['sale/create'], ['class' => 'btn btn-sm btn-success float-right']);
        }?>

    </div>

    <div class="box-body">

        <?php \yii\widgets\Pjax::begin(['id' => 'sale-index']); ?>

        <?= $this->render('/_templates/confirm-dialog.php') ?>

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
                    'attribute' => 'description',
                    'sortLinkOptions' => ['class' => 'sorting'],
                    'format' => 'html'
                ],
                [
                    'attribute' => 'images',
                    'format' => 'raw',
                    'value' => function ($model) {
                        if (empty($model['images'])) {
                            return null;
                        }
                        $html = '';
                        $sale = new Sale(['id' => $model['id']]);
                        $html = $html . '<img class="img-thumbnail" src="' . ImageHelper::getUrl($sale, $model['images']) . '"></img>';
                        return Html::a($html);
                    }
                ],
                [
                    'label' => Yii::t('app', "Start"),
                    'attribute' => 'start',
                    'value' => 'start',
                    'format' => 'html',
                    'filter' => UFDateRangePicker::widget([
                        'id' => 'date-range-start',
                        'model'=>$searchModel,
                        'attribute'=>'startTimeRange',
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
                    'headerOptions' => ['style' => 'min-width: 220px'],
                    'format' => 'html',
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'buttons' => [
                        'view' => function($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url);
                        },
                        'update' => function ($url, $model) {
                            if (!AccessChecker::hasPermission('sale.update')) {
                                return '';
                            }
                            $title = htmlspecialchars(Yii::t('app', 'Update "') . $model['name'] . '"');
                            return Html::a('<span class="glyphicon glyphicon-pencil"
                                data-title="' . $title . '"></span>', $url);
                        },
                        'delete' => function ($url, $model) {
                            if (!AccessChecker::hasPermission('sale.delete')) {
                                return '';
                            }
                            $title = htmlspecialchars(Yii::t('app', 'Sale Delete'));
                            $message = htmlspecialchars(
                                    Yii::t('app', 'Do you really want to delete "') . $model['name'] . Yii::t('app', '" sale') . '?'
                            );
                            return Html::tag('span', '', [
                                'class' => 'js-delete uf-listing-button uf-listing-button-delete glyphicon glyphicon-trash',
                                'data-url' => $url,
                                'data-title' => $title,
                                'data-confirm-message' => $message,
                                'data-notification-message' => [
                                    'success' => Yii::t('app', 'Sale have been successfully deleted.'),
                                    'error' => Yii::t('app', 'Sale have\'t been deleted.'),
                                ],
                            ]);
                        },
                    ],
                    'urlCreator' => function ($action, $model) {
                        switch ($action) {
                            case 'view': return Url::to(['sale/view', 'id' => $model['id']]);
                            case 'update': return Url::to(['sale/update', 'id' => $model['id']]);
                            case 'delete': return Url::to(['sale/delete', 'id' => $model['id']]);
                            default: return Url::to(['sale/view', 'id' => $model['id']]);
                        }
                    }
                ],
            ],
        ]); ?>
        <?php \yii\widgets\Pjax::end(); ?>
    </div>
</div>
