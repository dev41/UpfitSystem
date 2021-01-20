<?php

use app\src\entities\staff\Staff;
use app\src\entities\user\User;
use app\src\library\AccessChecker;
use yii\data\ActiveDataProvider;
use app\src\widget\UFGridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/**
 * @var View $this
 * @var int $staffId
 * @var bool $renderAjax
 * @var User[] $owners
 * @var ActiveDataProvider $staffDataProvider
 * @var $staff Staff
 */

$renderAjax = $renderAjax ?? false;
?>

<div class="js-club-position-container uf-listing <?= $renderAjax ? 'dialog-size' : '' ?>"
     data-staff_id="<?= $staff->id ?>">
    <div class="box-body">
        <div class="nav-tabs-buttons">
            <?php if (AccessChecker::hasActionAccess('staff-position-place.add-clubs-form')) {
                echo Html::button(\Yii::t('app', 'Add Position'), [
                    'data-title' => \Yii::t('app', 'Add Position'),
                    'data-message' => [
                        'success' => Yii::t('app', 'Position have been successfully added.'),
                        'error' => Yii::t('app', 'Position have\'t been added.'),
                    ],
                    'class' => 'js-button-add-position btn btn-sm btn-warning',
                ]);
            } ?>
        </div>
        <?= UFGridView::widget([
            'dataProvider' => $staffDataProvider,
            'columns' => [
                [
                    'attribute' => 'club_name',
                    'label' => Yii::t('app', 'Club name'),
                ],
                [
                    'attribute' => 'positions',
                    'label' => Yii::t('app', 'Positions'),
                ],

                [
                    'class' => 'yii\grid\ActionColumn',
                    'buttons' => [
                        'delete' => function ($url, $model) {
                            if (!AccessChecker::hasActionAccess('staff.delete-position-staff')) {
                                return '';
                            }
                            $title = htmlspecialchars(Yii::t('app', 'Delete staff from club'));
                            $message = htmlspecialchars(Yii::t('app',
                                    'Do you really want to delete from ') . $model['club_name'] . ' (' . $model['positions'] . ') ?');
                            return Html::tag('span', '', [
                                'class' => "js-button-delete-position uf-listing-button uf-listing-button-delete glyphicon glyphicon-trash",
                                'data-title' => $title,
                                'data-confirm-message' => $message,
                                'data-url' => $url,
                                'data-notification-message' => [
                                    'success' => Yii::t('app', 'Position have been successfully deleted.'),
                                    'error' => Yii::t('app', 'Position have\'t been deleted.'),
                                ],
                            ]);
                        },
                        'update' => function ($url, $model) {
                            if (!AccessChecker::hasActionAccess('staff.update-position-clubs-form')) {
                                return '';
                            }
                            $title = Yii::t('app', 'Update positions in club "') . $model['club_name'] . '"';
                            return Html::tag('span', '', [
                                'class' => "js-button-update-position uf-listing-button uf-listing-button-delete glyphicon glyphicon-pencil",
                                'data-title' => $title,
                                'data-url' => $url,
                                'data-message' => [
                                    'success' => Yii::t('app', 'Position have been successfully updated.'),
                                    'error' => Yii::t('app', 'Position have\'t been updated.'),
                                ],
                            ]);
                        },
                        'view' => function ($url, $model) {
                            return '';
                        }
                    ],
                    'urlCreator' => function ($action, $model) {
                        switch ($action) {
                            case 'update':
                                return Url::to([
                                    'staff-position-place/update-position-clubs-form',
                                    'club_id' => $model['club_id'],
                                    'user_id' => $model['user_id']
                                ]);
                            case 'delete':
                                return Url::to([
                                    'staff-position-place/delete-position-staff',
                                    'clubId' => $model['club_id'],
                                    'userId' => $model['user_id']
                                ]);
                            default:
                                return '';
                        }
                    }
                ],
            ],
        ]); ?>
    </div>
</div>