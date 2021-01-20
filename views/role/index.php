<?php

use app\src\entities\access\AccessRole;
use app\src\entities\access\AccessRoleSearch;
use app\src\library\AccessChecker;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use app\src\widget\UFGridView;
use yii\helpers\Url;
use yii\web\View;

/* @var View $this */
/* @var AccessRoleSearch $searchModel */
/* @var ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Roles');
$this->params['breadcrumbs'][] = $this->title;
$hasRoleUpdatePermission = AccessChecker::hasPermission('role.update');
?>

<div class="box uf-listing js-listing">

    <div class="box-header">
        <h3 class="box-title"><?= Yii::t('app', 'Role List') ?></h3>

        <?php if (AccessChecker::hasPermission('role.create')) {
            echo Html::a(Yii::t('app', 'Create Role'), ['role/create'], ['class' => 'btn btn-sm btn-success float-right']);
        } ?>

    </div>

    <div class="box-body">
        <?= $this->render('/_templates/confirm-dialog.php') ?>

        <?= UFGridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [

                [
                    'attribute' => 'type',
                    'label' => \Yii::t('app', 'ID')
                ],
                [
                    'attribute' => 'type',
                    'value' => function (AccessRole $data) {
                        return AccessRole::$types[$data->type];
                    },
                    'sortLinkOptions' => ['class' => 'sorting'],
                    'label' => \Yii::t('app', 'Type')
                ],
                [
                    'attribute' => 'name',
                    'sortLinkOptions' => ['class' => 'sorting'],
                    'label' => \Yii::t('app', 'Name')
                ],
                [
                    'attribute' => 'slug',
                    'sortLinkOptions' => ['class' => 'sorting'],
                    'label' => \Yii::t('app', 'Slug')
                ],

                [
                    'class' => 'yii\grid\ActionColumn',
                    'buttons' => [
                        'view' => function ($url, $model) {
                            return '';
                        },
                        'update' => function ($url, $model) use ($hasRoleUpdatePermission) {
                            if (
                                !$hasRoleUpdatePermission ||
                                $model['slug'] === AccessRole::ROLE_SUPER_ADMIN
                            ) {
                                return '';
                            }
                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url);
                        },
                        'delete' => function ($url, $model) {
                            if (!AccessChecker::hasAccess('role.delete') ||
                                $model['type'] === AccessRole::TYPE_SYSTEM) {
                                return '';
                            }
                            $title = htmlspecialchars(Yii::t('app', 'Delete role'));
                            $message = htmlspecialchars(Yii::t('app', 'Do you really want to delete "') . $model['name'] . '"?');

                            return Html::tag('span', '', [
                                'class' => 'js-delete uf-listing-button uf-listing-button-delete glyphicon glyphicon-trash',
                                'data-url' => $url,
                                'data-title' => $title,
                                'data-confirm-message' => $message,
                                'data-notification-message' => [
                                    'success' => Yii::t('app', 'Role have been successfully deleted.'),
                                    'error' => Yii::t('app', 'Role have\'t been deleted.'),
                                ],
                            ]);
                        }
                    ],
                    'urlCreator' => function ($action, $model) {
                        switch ($action) {
                            case 'update': return Url::to(['role/update', 'id' => $model['id']]);
                            case 'delete': return Url::to(['role/delete', 'id' => $model['id']]);
                            default: return Url::to(['role/view', 'id' => $model['id']]);
                        }
                    }
                ],
            ],
        ]); ?>
    </div>
</div>
