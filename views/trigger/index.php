<?php

use app\src\entities\access\AccessPermission;
use app\src\entities\trigger\Trigger;
use app\src\helpers\TriggerHelper;
use app\src\library\AccessChecker;
use yii\data\ActiveDataProvider;
use app\src\widget\UFGridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/* @var View $this */
/* @var ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Trigger');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="box uf-listing js-listing">

    <div class="box-header">
        <h3 class="box-title"><?= Yii::t('app', 'Trigger List') ?></h3>

        <?php if (AccessChecker::hasControllerAccess('trigger', AccessPermission::TYPE_CREATE)) {
            echo Html::a(Yii::t('app', 'Create Trigger'), ['trigger/create'], ['class' => 'btn btn-sm btn-success float-right']);
        } ?>

    </div>

    <div class="box-body">
        <?= $this->render('/_templates/confirm-dialog.php') ?>

        <?= UFGridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                [
                    'attribute' => 'id',
                    'label' => Yii::t('app', 'ID')
                ],
                [
                    'attribute' => 'name',
                    'sortLinkOptions' => ['class' => 'sorting'],
                    'label' => Yii::t('app', 'Name'),
                ],
                [
                    'attribute' => 'types',
                    'value' => function ($model) {
                        return TriggerHelper::getTypeLabels($model['types']);
                    },
                    'sortLinkOptions' => ['class' => 'sorting'],
                    'label' => Yii::t('app', 'Type'),
                ],
                [
                    'attribute' => 'event',
                    'value' => function ($model) {
                        return Trigger::EVENT_LABELS[$model['event']];
                    },
                    'sortLinkOptions' => ['class' => 'sorting'],
                    'label' => Yii::t('app', 'Event'),
                ],
                [
                    'attribute' => 'template',
                    'label' => Yii::t('app', 'Template')
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'buttons' => [
                        'view' => function ($url) {
                            return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url);
                        },
                        'update' => function ($url, $model) {
                            if (!AccessChecker::hasPermission('trigger.update')) {
                                return '';
                            }
                            $title = htmlspecialchars(Yii::t('app', 'Update "') . $model['name'] . '"');
                            return Html::a('<span class="glyphicon glyphicon-pencil"
                                data-title="' . $title . '"></span>', $url);
                        },
                        'delete' => function ($url, $model) {
                            if (!AccessChecker::hasPermission('trigger.delete')) {
                                return '';
                            }
                            $destination = TriggerHelper::getTypeLabels($model['types']);
                            $title = htmlspecialchars(Yii::t('app', 'Delete ') . $destination);
                            $message = htmlspecialchars(Yii::t('app', 'Do you really want to delete "') . $model['name'] . Yii::t('app',  '" trigger?'));

                            return Html::tag('span', '', [
                                'class' => 'js-delete uf-listing-button uf-listing-button-delete glyphicon glyphicon-trash',
                                'data-url' => $url,
                                'data-title' => $title,
                                'data-confirm-message' => $message,
                                'data-notification-message' => [
                                    'success' => Yii::t('app', 'Trigger have been successfully deleted.'),
                                    'error' => Yii::t('app', 'Trigger have\'t been deleted.'),
                                ],
                            ]);
                        },
                    ],
                    'urlCreator' => function ($action, $model) {
                        switch ($action) {
                            case 'view': return Url::to(['trigger/view', 'id' => $model['id']]);
                            case 'update': return Url::to(['trigger/update', 'id' => $model['id']]);
                            case 'delete': return Url::to(['trigger/delete', 'id' => $model['id']]);
                            default: return Url::to(['trigger/view', 'id' => $model['id']]);
                        }
                    }
                ],
            ],
        ]); ?>
    </div>
</div>
