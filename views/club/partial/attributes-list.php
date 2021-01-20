<?php

use app\src\entities\attribute\Attribute;
use app\src\library\AccessChecker;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use app\src\widget\UFGridView;
use yii\helpers\Url;
use yii\web\View;

/**
 * @var View $this
 * @var ActiveDataProvider $attributesDataProvider
 */

$hasCreateAttributePermission = AccessChecker::hasPermission('attribute-club.change-clubs-attributes');
$hasDeleteAttributePermission = AccessChecker::hasPermission('attribute-club.delete-clubs-attributes');
?>

<div class="js-attributes-container uf-listing">
    <div class="box-body">
        <div class="nav-tabs-buttons">
            <?php if ($hasCreateAttributePermission) {
                echo Html::button(\Yii::t('app', 'Add Attribute'), [
                    'data-title' => \Yii::t('app', 'Add Attribute'),
                    'data-message' => [
                        'success' => Yii::t('app', 'Attribute have been successfully added.'),
                        'error' => Yii::t('app', 'Attribute have\'t been added.'),
                    ],
                    'class' => 'js-button-add-attribute btn btn-sm btn-warning',
                ]);
            } ?>
        </div>

        <?= UFGridView::widget([
            'dataProvider' => $attributesDataProvider,
            'columns' => [
                [
                    'attribute' => 'id',
                    'label' => Yii::t('app', 'ID'),
                ],
                [
                    'attribute' => 'name',
                    'label' => Yii::t('app', 'Name'),
                ],
                [
                    'attribute' => 'value',
                    'format' => 'raw',
                    'label' => Yii::t('app', 'Value'),
                    'value' => function ($model) {
                        if ((int) $model['type'] === Attribute::TYPE_BOOLEAN) {

                            $statusClasses = (bool) $model['value'] ?
                                'glyphicon-ok-circle text-success' : 'glyphicon-remove text-danger';
                            return Html::tag('span', '', [
                                'class' => 'glyphicon ' . $statusClasses,
                            ]);
                        } else {
                            return $model['value'];
                        }
                    }
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'buttons' => [
                        'delete' => function ($url, $model) use ($hasDeleteAttributePermission) {
                            if (!$hasDeleteAttributePermission) {
                                return '';
                            }
                            $title = htmlspecialchars(Yii::t('app', 'Delete attribute'));
                            $message = htmlspecialchars(Yii::t('app',
                                    'Do you really want to delete') . '"' . $model['name'] . '" ?');

                            return Html::tag('span', '', [
                                'class' => "js-delete-attribute uf-listing-button uf-listing-button-delete glyphicon glyphicon-trash",
                                'data-title' => $title,
                                'data-confirm-message' => $message,
                                'data-url' => $url,
                                'data-notification-message' => [
                                    'success' => Yii::t('app', 'Attribute have been successfully deleted.'),
                                    'error' => Yii::t('app', 'Attribute have\'t been deleted.'),
                                ],
                            ]);
                        },
                        'update' => function ($url, $model) use ($hasCreateAttributePermission) {
                            if (!$hasCreateAttributePermission) {
                                return '';
                            }
                            $title = Yii::t('app', 'Update ') . '"' . $model['name'] . '"' . Yii::t('app', ' attribute');
                            return Html::tag('span', '', [
                                'class' => "js-button-update-attribute uf-listing-button uf-listing-button-edit glyphicon glyphicon-pencil",
                                'data-title' => $title,
                                'data-url' => $url,
                                'data-message' => [
                                    'success' => Yii::t('app', 'Attribute have been successfully updated.'),
                                    'error' => Yii::t('app', 'Attribute have\'t been updated.'),
                                ],
                            ]);
                        },
                        'view' => function () {
                            return '';
                        }

                    ],
                    'urlCreator' => function ($action, $model) {
                        switch ($action) {
                            case 'update': return Url::to(['attribute-club/get-create-form', 'attributeId' => $model['id'], 'clubId' => $model['parent_id']]);
                            case 'delete': return Url::to(['attribute-club/delete', 'attributeId' => $model['id'], 'clubId' => $model['parent_id']]);
                            default: return '';
                        }
                    }
                ],
            ],
        ]); ?>
    </div>
</div>