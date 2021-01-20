<?php

use app\src\entities\user\User;
use app\src\entities\staff\StaffPositionPlaceSearch;
use app\src\library\AccessChecker;
use yii\data\ActiveDataProvider;
use app\src\widget\UFDateRangePicker;
use app\src\widget\UFGridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/**
 * @var View $this
 * @var int $clubId
 * @var User[] $owners
 * @var StaffPositionPlaceSearch $staffPositionPlaceSearch
 * @var ActiveDataProvider $staffDataProvider
 */

$renderAjax = $renderAjax ?? false;
$hasDeletePositionsPermission = AccessChecker::hasPermission('club.delete-position-staff');
$hasUpdatePositionsPermission = AccessChecker::hasPermission('staff-position-place.update-staff-form');
$hasUpdatePositionsPermission = AccessChecker::hasPermission('staff.update');
$hasDeleteCustomerPermission = AccessChecker::hasPermission('customer-place.delete-customer');
?>

<div class="js-staff-position-container uf-listing <?=  $renderAjax ? 'dialog-size' : '' ?>" data-club_id="<?= $clubId ?>">
    <div class="box-body">

        <div class="nav-tabs-buttons">
            <?php if (AccessChecker::hasPermission('staff-position-place.add-staff-form')) {
                echo Html::button(\Yii::t('app', 'Add Positions'), [
                    'data-title' => \Yii::t('app', 'Add Staff'),
                    'data-message' => [
                        'success' => Yii::t('app', 'Staffs positions have been successfully added.'),
                        'error' => Yii::t('app', 'Staffs positions have\'t been added.'),
                    ],
                    'class' => 'js-button-add-staff btn btn-sm btn-warning',
                ]);
            } ?>

            <?php if (AccessChecker::hasPermission('staff.create')) {
                echo Html::button(\Yii::t('app', 'Create Staff'), [
                    'data-title' => \Yii::t('app', 'Staff Create'),
                    'data-message' => [
                        'success' => Yii::t('app', 'Staff has been successfully created.'),
                        'error' => Yii::t('app', 'Staff has\'t been created.'),
                    ],
                    'class' => 'js-button-create-staff btn btn-sm btn-warning']);
            } ?>
        </div>

        <?php \yii\widgets\Pjax::begin(['id' => 'club-spp']); ?>

        <?= UFGridView::widget([
            'dataProvider' => $staffDataProvider,
            'filterModel' => $staffPositionPlaceSearch,
            'columns' => [

                'id',
                [
                    'attribute' => 'username',
                    'label' => Yii::t('app', 'Username'),
                    'sortLinkOptions' => ['class' => 'sorting'],
                ],
                [
                    'attribute' =>   'fullname',
                    'sortLinkOptions' => ['class' => 'sorting'],
                ],
                [
                    'attribute' =>   'email',
                    'sortLinkOptions' => ['class' => 'sorting'],
                ],
                [
                    'attribute' =>   'phone',
                    'sortLinkOptions' => ['class' => 'sorting'],
                ],
                [
                    'label' => Yii::t('app', "Date of Birthday"),
                    'attribute' => 'birthdayTimeRange',
                    'value' => 'birthday',
                    'format' => 'html',
                    'filter' => UFDateRangePicker::widget([
                        'id' => 'date-range-birthday',
                        'model' => $staffPositionPlaceSearch,
                        'attribute' => 'birthdayTimeRange',
                    ]),
                ],
                [
                    'attribute' =>   'address',
                    'sortLinkOptions' => ['class' => 'sorting'],
                ],
                [
                    'attribute' =>   'description',
                    'sortLinkOptions' => ['class' => 'sorting'],
                ],
                [
                    'attribute' => 'positions',
                    'label' => Yii::t('app', 'Positions'),
                    'format' => 'raw',
                    'value' => function ($model) use ($hasUpdatePositionsPermission) {
                        if (!$hasUpdatePositionsPermission) {
                            return '';
                        }
                        $title = Yii::t('app', 'Update ') . '"' . $model['username'] . '"' . Yii::t('app', ' positions');

                        return $model['positions']
                            . Html::tag('span', '', [
                                'class' => "right js-button-update-position uf-listing-button uf-listing-button-edit glyphicon glyphicon-pencil",
                                'style' => 'float:right',
                                'data-title' => $title,
                                'data-url' => Url::to([
                                    'staff-position-place/update-staff-form',
                                    'club_id' => $model['club_id'],
                                    'user_id' => $model['user_id']]),
                                'data-message' => [
                                    'success' => Yii::t('app', 'Staffs positions have been successfully updated.'),
                                    'error' => Yii::t('app', 'Staffs positions have\'t been updated.'),
                                ],
                            ]);
                    },
                    'filter' => $staffPositionPlaceSearch->getPositionsFilterOptions(),
                    'filterInputOptions' => ['prompt' => Yii::t('app', 'selected_all'), 'class' => 'form-control', 'id' => null],
                    'sortLinkOptions' => ['class' => 'sorting'],
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'buttons' => [
                        'delete' => function ($url, $model) use ($hasDeletePositionsPermission) {
                            if (!$hasDeletePositionsPermission) {
                                return '';
                            }
                            $title = htmlspecialchars(Yii::t('app', 'Delete staff from club'));
                            $message = htmlspecialchars(Yii::t('app',
                                    'Do you really want to delete ') . '"' . $model['username'] . '" (' . $model['positions'] . ') ?');
                            return Html::tag('span', '', [
                                'class' => "js-button-delete-staff uf-listing-button uf-listing-button-edit glyphicon glyphicon-trash",
                                'data-title' => $title,
                                'data-confirm-message' => $message,
                                'data-url' => $url,
                                'data-notification-message' => [
                                    'success' => Yii::t('app', 'Staffs positions have been successfully deleted.'),
                                    'error' => Yii::t('app', 'Staffs positions have\'t been deleted.'),
                                ],
                            ]);
                        },
                        'update' => function ($url, $model) use ($hasUpdatePositionsPermission) {
                            if (!$hasUpdatePositionsPermission) {
                                return '';
                            }
                            $title = Yii::t('app', 'Update ') . '"' . $model['username'] . '"';
                            return Html::tag('span', '', [
                                'class' => "js-button-update-staff uf-listing-button uf-listing-button-edit glyphicon glyphicon-pencil",
                                'data-title' => $title,
                                'data-staff_id' => $model['id'],
                                'data-url' => $url,
                                'data-message' => [
                                    'success' => Yii::t('app', 'Staffs positions have been successfully updated.'),
                                    'error' => Yii::t('app', 'Staffs positions have\'t been updated.'),
                                ],
                            ]);
                        },
                        'view' => function () {
                            return '';
                        }
                    ],

                    'urlCreator' => function ($action, $model) {
                        switch ($action) {
                            case 'update': return Url::to([
                                'staff/get-update-form',
                                'id' => $model['id'],
                                'clubId' => $model['club_id'],
                            ]);
                            case 'delete': return Url::to([
                                'staff-position-place/delete-staff',
                                'clubId' => $model['club_id'],
                                'userId' => $model['user_id']
                            ]);
                            default: return '';
                        }
                    }
                ],
            ],
        ]); ?>

        <?php \yii\widgets\Pjax::end(); ?>

    </div>
</div>
