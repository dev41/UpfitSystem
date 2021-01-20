<?php

use app\src\entities\customer\CustomerSearch;
use app\src\library\AccessChecker;
use yii\data\ActiveDataProvider;
use app\src\widget\UFDateRangePicker;
use yii\helpers\Html;
use app\src\widget\UFGridView;
use yii\helpers\Url;
use yii\web\View;

/* @var View $this */
/* @var CustomerSearch $searchModel */
/* @var ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Customer');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="box uf-form-create uf-listing js-listing">

    <div class="box-header">
        <h3 class="box-title"><?= Yii::t('app', 'Customer List') ?></h3>
    </div>

    <div class="box-body">
        <?= $this->render('/_templates/confirm-dialog.php') ?>
        <div class="customer-index">

        <?php \yii\widgets\Pjax::begin(['id' => 'customer-index']); ?>

        <?= UFGridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                'id',
                [
                    'attribute' => 'username',
                    'sortLinkOptions' => ['class' => 'sorting'],
                ],
                [
                    'attribute' => 'fullname',
                    'sortLinkOptions' => ['class' => 'sorting'],
                ],
                [
                    'attribute' => 'email',
                    'sortLinkOptions' => ['class' => 'sorting'],
                ],
                [
                    'attribute' => 'card_number',
                    'label' => \Yii::t('app', 'Card Number')
                ],
                'phone',
                [
                    'label' => Yii::t('app', "Date of Birthday"),
                    'attribute' => 'birthdayTimeRange',
                    'value' => 'birthday',
                    'format' => 'html',
                    'filter' => UFDateRangePicker::widget([
                        'id' => 'date-range-birthday',
                        'model' => $searchModel,
                        'attribute' => 'birthdayTimeRange',
                    ]),
                ],

                [
                    'class' => 'yii\grid\ActionColumn',
                    'buttons' => [
                        'view' => function ($url, $model) {
                            return '';
                        },
                        'update' => function ($url, $model) {
                            if (!AccessChecker::hasPermission('customer.update')) {
                                return '';
                            }
                            $title = htmlspecialchars(Yii::t('app', 'Update customer'));
                            return Html::a('<span class="glyphicon glyphicon-pencil" data-title="' . $title . '"></span>', $url);
                        },
                        'delete' => function ($url, $model) {
                            if (!AccessChecker::hasPermission('customer.delete')) {
                                return '';
                            }
                            $title = htmlspecialchars(Yii::t('app', 'Customer Delete'));
                            $message = htmlspecialchars(
                                    Yii::t('app', 'Do you really want to delete "') . $model['username'] . '"?'
                            );

                            return Html::tag('span', '', [
                                'class' => 'js-delete uf-listing-button uf-listing-button-delete glyphicon glyphicon-trash',
                                'data-url' => $url,
                                'data-title' => $title,
                                'data-confirm-message' => $message,
                                'data-notification-message' => [
                                    'success' => Yii::t('app', 'Customer have been successfully deleted.'),
                                    'error' => Yii::t('app', 'Customer have\'t been deleted.'),
                                ],
                            ]);
                        }
                    ],
                    'urlCreator' => function ($action, $model) {
                        switch ($action) {
                            case 'update': return Url::to(['customer/update', 'id' => $model['id']]);
                            case 'delete': return Url::to(['customer/delete', 'id' => $model['id']]);
                            default: return Url::to(['customer/view', 'id' => $model['id']]);
                        }
                    }
                ],
            ],
        ]); ?>

        <?php \yii\widgets\Pjax::end(); ?>
    </div>
</div>
