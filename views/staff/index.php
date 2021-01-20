<?php

use app\src\entities\staff\StaffSearch;
use app\src\helpers\UserHelper;
use app\src\library\AccessChecker;
use yii\data\ActiveDataProvider;
use app\src\widget\UFDateRangePicker;
use yii\helpers\Html;
use app\src\widget\UFGridView;
use yii\helpers\Url;
use yii\web\View;

/* @var View $this */
/* @var StaffSearch $searchModel */
/* @var ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Staff');
$this->params['breadcrumbs'][] = $this->title;

$hasUpdateAttributePermission = AccessChecker::hasPermission('staff.update');
$hasDeleteAttributePermission = AccessChecker::hasPermission('staff.delete');
?>

<div class="box uf-form-create uf-listing js-listing">

    <div class="box-header">
        <h3 class="box-title"><?= Yii::t('app', 'Staff List') ?></h3>
    </div>

    <div class="box-body">

        <?= $this->render('/_templates/confirm-dialog.php') ?>
        <div class="staff-index">

        <?php \yii\widgets\Pjax::begin(['id' => 'staff-index']); ?>

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
                    'label' => Yii::t('app', 'Club'),
                    'attribute' => 'clubIds',
                    'value' => 'club_names',
                    'filter' => $searchModel->getClubsFilterOptions(),
                    'filterInputOptions' => ['prompt' => Yii::t('app', 'selected_all'), 'class' => 'form-control', 'id' => null],
                    'headerOptions' => ['style' => 'min-width: 100px'],
                ],
                [
                    'label' => Yii::t('app', 'Position'),
                    'attribute' => 'positions',
                    'value' => 'positions',
                    'filter' => $searchModel->getPositionsFilterOptions(),
                    'filterInputOptions' => ['prompt' => Yii::t('app', 'selected_all'), 'class' => 'form-control', 'id' => null],
                    'headerOptions' => ['style' => 'min-width: 100px'],
                ],
                [
                    'label' => Yii::t('app', 'Role'),
                    'attribute' => 'role_id',
                    'value' => 'role',
                    'filter' => $searchModel->getRolesFilterOptions(),
                    'filterInputOptions' => ['prompt' => Yii::t('app', 'selected_all'), 'class' => 'form-control', 'id' => null],
                    'headerOptions' => ['style' => 'min-width: 100px'],
                ],
                [
                    'attribute' => 'email',
                    'sortLinkOptions' => ['class' => 'sorting'],
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
                        'update' => function ($url, $model) use ($hasUpdateAttributePermission) {
                            if (!$hasUpdateAttributePermission) {
                                return '';
                            }
                            $positionsLabel = UserHelper::getPositionsLabelByStaffId($model['id']);
                            $title = htmlspecialchars(Yii::t('app', 'Update ') . $positionsLabel . '.');
                            return Html::a('<span class="glyphicon glyphicon-pencil"
                                data-title="' . $title . '"></span>', $url);
                        },
                        'delete' => function ($url, $model) use ($hasDeleteAttributePermission) {
                            if (!$hasDeleteAttributePermission) {
                                return '';
                            }
                            $positionsLabel = UserHelper::getPositionsLabelByStaffId($model['id']);
                            $title = htmlspecialchars(Yii::t('app', 'Delete ') . $positionsLabel);
                            $message = htmlspecialchars(
                                Yii::t('app', 'Do you really want to delete "') . $model['username'] . '"?'
                            );

                            return Html::tag('span', '', [
                                'class' => 'js-delete uf-listing-button uf-listing-button-delete glyphicon glyphicon-trash',
                                'data-url' => $url,
                                'data-title' => $title,
                                'data-confirm-message' => $message,
                                'data-notification-message' => [
                                    'success' => Yii::t('app', 'Staff have been successfully deleted.'),
                                    'error' => Yii::t('app', 'Staff have\'t been deleted.'),
                                ],
                            ]);
                        }
                    ],
                    'urlCreator' => function ($action, $model) {
                        switch ($action) {
                            case 'update': return Url::to(['staff/update', 'id' => $model['id']]);
                            case 'delete': return Url::to(['staff/delete', 'id' => $model['id']]);
                            default: return Url::to(['staff/view', 'id' => $model['id']]);
                        }
                    }
                ],
            ],
        ]); ?>

        <?php \yii\widgets\Pjax::end(); ?>
    </div>
</div>