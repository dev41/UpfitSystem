<?php

use app\src\entities\translate\TranslationSearch;
use app\src\entities\translate\Language;
use app\src\library\AccessChecker;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel TranslationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Translations');
$hasUpdateClubPermission = AccessChecker::hasPermission('translation.update');
$hasDeleteClubPermission = AccessChecker::hasPermission('translation.delete');
?>
<div class="box uf-listing js-listing">


    <div class="box-header">
        <h3 class="box-title"><?= Html::encode($this->title) ?></h3>

        <?php if (AccessChecker::hasAccess('translation.create')) {
            echo Html::a(Yii::t('app', 'Create Translation'), ['translation/create'], ['class' => 'btn btn-sm btn-success float-right']);
        } ?>
    </div>

    <div class="box-body">

        <div class="translation-index">

            <?php Yii::$container->set('yii\widgets\ActiveField', ['template' => "{label}\n{input}\n{hint}\n{error}"]); ?>

            <?= $this->render('/_templates/confirm-dialog.php') ?>

            <?= GridView::widget([
                'summary' => '',

                'tableOptions' => [
                    'class' => 'table table-bordered table-hover'
                ],

                'pager' => [
                    'options' => [
                        'tag' => 'ul',
                        'class' => 'pagination list-pagination float-right',
                    ],
                    'prevPageLabel' => '<div style="border: none">' . \Yii::t('app', 'Previous') . '</div>',
                    'nextPageLabel' => '<div style="border: none">' . \Yii::t('app', 'Next') . '</div>',
                ],

                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    [
                        'attribute' => 'id',
                        'label' => Yii::t('app', 'ID'),
                        'sortLinkOptions' => ['class' => 'sorting'],
                    ],
                    [
                        'attribute' => 'language',
                        'format' => 'html',
                        'filter' => Language::getList(),
                        'filterInputOptions' => ['prompt' => Yii::t('app', 'selected_all'), 'class' => 'form-control', 'id' => null],
                        'value' => function ($data) {
                            return Html::tag('div', '', ['class' => 'flag flag-' . $data->language]);
                        },
                        'sortLinkOptions' => ['class' => 'sorting'],
                    ],
                    [
                        'attribute' => 'sourceMessage',
                        'label' => Yii::t('app', 'sourceMessage'),
                        'sortLinkOptions' => ['class' => 'sorting'],
                    ],
                    [
                        'class' => 'kartik\grid\EditableColumn',
                        'attribute' => 'translation',
                        'pageSummary' => true,
                        'format' => 'ntext',
                        'editableOptions' => function ($model, $key, $index) {
                            return [
                                'size' => 'lg',
                                'formOptions' => ['action' => ['update-translation']],
                            ];
                        },
                        'sortLinkOptions' => ['class' => 'sorting'],
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'buttons' => [
                            'view' => function ($url, $model) {
                                return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url);
                            },
                            'update' => function ($url, $model) use ($hasUpdateClubPermission) {
                                if (!$hasUpdateClubPermission) {
                                    return '';
                                }
                                $title = htmlspecialchars(Yii::t('app', 'Update "') . $model['id'] . '"');
                                return Html::a('<span class="glyphicon glyphicon-pencil"
                                    data-title="' . $title . '"></span>', $url);
                            },
                            'delete' => function ($url, $model) use ($hasDeleteClubPermission) {
                                if (!$hasDeleteClubPermission) {
                                    return '';
                                }

                                $title = htmlspecialchars(Yii::t('app', 'Translation Delete'));
                                $message = htmlspecialchars(Yii::t('app', 'Do you really want to delete "') . $model['id'] . Yii::t('app', '" translation') . '?');
                                return Html::tag('span', '', [
                                    'class' => 'js-delete uf-listing-button uf-listing-button-delete glyphicon glyphicon-trash',
                                    'data-url' => $url,
                                    'data-title' => $title,
                                    'data-confirm-message' => $message,
                                    'data-notification-message' => [
                                        'success' => Yii::t('app', 'Translation have been successfully deleted.'),
                                        'error' => Yii::t('app', 'Translation have\'t been deleted.'),
                                    ],
                                ]);
                            },
                        ],
                        'urlCreator' => function ($action, $model) {
                            switch ($action) {
                                case 'update': return Url::to(['translation/update', 'id' => $model['id'], 'language' => $model['language']]);
                                case 'delete': return Url::to(['translation/delete', 'id' => $model['id'], 'language' => $model['language']]);
                                default: return Url::to(['translation/view', 'id' => $model['id'], 'language' => $model['language']]);
                            }
                        }
                    ],
                ],
                'striped' => false,
            ]); ?>
        </div>
    </div>
</div>
