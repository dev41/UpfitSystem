<?php

use app\src\entities\customer\CustomerPlace;
use app\src\library\AccessChecker;
use yii\data\ActiveDataProvider;
use app\src\widget\UFGridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/**
 * @var View $this
 * @var ActiveDataProvider $clientsDataProvider
 */

$hasSetStatusPermission = AccessChecker::hasPermission('customer-place.set-status');
$hasCreateAttributePermission = AccessChecker::hasPermission('customer-place.add-customers-form');
$hasDeleteCustomerPermission = AccessChecker::hasPermission('customer-place.delete-customer');
?>

<div class="js-customers-container uf-listing">
    <div class="box-body">
        <div class="nav-tabs-buttons">
            <?php if (AccessChecker::hasActionAccess('customer-place.add-customers-form')) {
                echo Html::button(\Yii::t('app', 'Add Customers'), [
                    'data-title' => \Yii::t('app', 'Add Customer'),
                    'data-message' => [
                        'success' => Yii::t('app', 'Customer have been successfully added.'),
                        'error' => Yii::t('app', 'Customer have\'t been added.'),
                    ],
                    'class' => 'js-button-add-customer btn btn-sm btn-warning',
                ]);
            } ?>

            <?php if (AccessChecker::hasPermission('customer.create')) {
                echo Html::button(\Yii::t('app', 'Create Customer'), [
                    'data-title' => \Yii::t('app', 'Create Customer'),
                    'data-message' => [
                        'success' => Yii::t('app', 'Customer have been successfully created.'),
                        'error' => Yii::t('app', 'Customer have\'t been created.'),
                    ],
                    'class' => 'js-button-create-customer btn btn-sm btn-warning']);
            } ?>
        </div>

        <?php \yii\widgets\Pjax::begin(['id' => 'club-cp']); ?>

        <?= UFGridView::widget([
            'dataProvider' => $clientsDataProvider,
            'columns' => [
                [
                    'attribute' => 'username',
                    'label' => Yii::t('app', 'Username'),
                ],
                [
                    'attribute' => 'fullname',
                    'label' => Yii::t('app', 'Fullname'),
                ],
                [
                    'attribute' => 'email',
                    'label' => Yii::t('app', 'Email'),
                ],
                [
                    'attribute' => 'card_number',
                    'label' => Yii::t('app', 'Card number'),
                ],
                [
                    'attribute' => 'phone',
                    'label' => Yii::t('app', 'Phone'),
                ],
                [
                    'label' => Yii::t('app', 'Date of Birthday'),
                    'attribute' => 'birthday',
                    'value' => 'birthday',
                ],
                [
                    'attribute' => 'description',
                    'label' => Yii::t('app', 'Description'),
                ],
                [
                    'label' => Yii::t('app', 'Status'),
                    'format' => 'raw',
                    'contentOptions' => ['class' => 'text-center'],
                    'value' => function ($model) use ($hasSetStatusPermission) {
                        $statusClasses = (int) $model['status'] === CustomerPlace::STATUS_PENDING ?
                            'label-danger' : 'label-success';
                        $statusLabel = (int) $model['status'] === CustomerPlace::STATUS_PENDING ?
                            Yii::t('app', 'Pending') : Yii::t('app', 'Approved');
                        $jsClass = $hasSetStatusPermission ? 'js-set-status' : '';
                        return Html::tag('span', $statusLabel, [
                            'class' => $jsClass . ' uf-customer-change-status label ' . $statusClasses,
                            'data-url' => Url::to([
                                'customer-place/set-status', 'userId' => $model['user_id'], 'clubId' => $model['club_id']
                            ]),
                            'data-message' => [
                                'success' => Yii::t('app', 'Customers status have been successfully updated.'),
                                'error' => Yii::t('app', 'Customers status have\'t been updated.'),
                            ],
                            'data-labels' => [
                                'success' => Yii::t('app', 'Approved'),
                                'warning' => Yii::t('app', 'Pending'),
                            ],
                        ]);
                    }

                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'buttons' => [
                        'delete' => function ($url, $model) use ($hasDeleteCustomerPermission) {
                            if (!$hasDeleteCustomerPermission) {
                                return '';
                            }
                            $title = htmlspecialchars(Yii::t('app', 'Delete customer from club'));
                            $message = htmlspecialchars(Yii::t('app',
                                    'Do you really want to delete ') . '"' . $model['username'] . '" ?');

                            return Html::tag('span', '', [
                                'class' => "js-button-delete-customer uf-listing-button uf-listing-button-edit glyphicon glyphicon-trash",
                                'data-title' => $title,
                                'data-confirm-message' => $message,
                                'data-url' => $url,
                                'data-notification-message' => [
                                    'success' => Yii::t('app', 'Customer have been successfully deleted.'),
                                    'error' => Yii::t('app', 'Customer have\'t been deleted.'),
                                ],
                            ]);
                        },
                       'update' => function ($url, $model) use ($hasCreateAttributePermission) {
                            if (!$hasCreateAttributePermission) {
                                return '';
                            }
                           $title = Yii::t('app', 'Update ') . '"' . $model['username'] . '"' . Yii::t('app', ' customer');
                           return Html::tag('span', '', [
                               'class' => "js-button-update-customer uf-listing-button uf-listing-button-edit glyphicon glyphicon-pencil",
                               'data-title' => $title,
                               'data-url' => $url,
                               'data-customer-id' => $model['user_id'],
                               'data-message' => [
                                   'success' => Yii::t('app', 'Customer have been successfully updated.'),
                                   'error' => Yii::t('app', 'Customer have\'t been updated.'),
                               ],
                           ]);
                       },
                        'view' => function () {
                            return '';
                        }

                    ],
                    'urlCreator' => function ($action, $model) {
                        switch ($action) {
                            case 'update': return Url::to(['customer-place/get-update-form',
                                'customer_id' => $model['user_id'],
                                'club_id' => $model['club_id']
                            ]);
                            case 'delete': return Url::to([
                                    'customer-place/delete-customer',
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
