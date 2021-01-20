<?php

use app\src\entities\access\AccessPermission;
use app\src\entities\place\Club;
use app\src\entities\place\Place;
use app\src\entities\place\Subplace;
use app\src\entities\place\SubplaceSearch;
use app\src\library\AccessChecker;
use kartik\datecontrol\DateControl;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use app\src\helpers\ImageHelper;
use yii\helpers\Html;
use app\src\widget\UFGridView;
use yii\helpers\Url;
use yii\web\View;

/* @var View $this */
/* @var SubplaceSearch $searchModel */
/* @var ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Places');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="box uf-listing js-listing">

    <div class="box-header">
        <h3 class="box-title"><?= Yii::t('app', 'Place List') ?></h3>

        <?php if (AccessChecker::hasControllerAccess('place', AccessPermission::TYPE_CREATE)) {
            echo Html::a(Yii::t('app', 'Create Place'), ['place/create'], ['class' => 'btn btn-sm btn-success float-right']);
        } ?>

    </div>

    <div class="box-body">

        <?php \yii\widgets\Pjax::begin(['id' => 'place-index']); ?>

        <?= $this->render('/_templates/confirm-dialog.php') ?>
        <div class="place-index">

        <?= UFGridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                'id',
                [
                    'attribute' => 'name',
                    'sortLinkOptions' => ['class' => 'sorting'],
                ],
                [
                    'attribute' => 'type',
                    'sortLinkOptions' => ['class' => 'sorting'],
                    'value' => function ($model) {
                        return Place::getLabelByType($model['type']);
                    }
                ],
                [
                    'attribute' => 'address',
                    'sortLinkOptions' => ['class' => 'sorting'],
                ],
                [
                    'attribute' => 'image',
                    'format' => 'raw',
                    'value' => function ($model) {
                        if (empty($model['image'])) {
                            return null;
                        }
                        $images = array_filter(explode(',', $model['image']));
                        $html = '';
                        foreach ($images as $image) {
                            $subplace = new Subplace(['id' => $model['id']]);
                            $html = $html . '<img class="img-thumbnail" src="' . ImageHelper::getUrl($subplace, $image) . '"></img>';
                        }
                        return Html::a($html);
                    }
                ],
                [
                    'attribute' => 'description',
                    'sortLinkOptions' => ['class' => 'sorting'],
                ],
                [
                    'label' => Yii::t('app', 'Club'),
                    'attribute' => 'parent_id',
                    'value' => 'club_name',
                    'filter' => ArrayHelper::map(Club::getAll(), 'id', 'name'),
                    'filterInputOptions' => ['prompt' => Yii::t('app', 'selected_all'), 'class' => 'form-control', 'id' => null],
                    'headerOptions' => ['style' => 'min-width: 150px'],
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
                        'view' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url);
                        },
                        'update' => function ($url, $model) {
                            if (!AccessChecker::hasAccess('place.update')) {
                                return '';
                            }
                            $title = htmlspecialchars(Yii::t('app', 'Update "') . $model['name'] . '"');
                            return Html::a('<span class="glyphicon glyphicon-pencil"
                                data-title="' . $title . '"></span>', $url);
                        },
                        'delete' => function ($url, $model) {
                            if (!AccessChecker::hasAccess('place.delete')) {
                                return '';
                            }
                            $typeLabel = Place::getLabelByType($model['type']);
                            $title = htmlspecialchars($typeLabel . ' ' . Yii::t('app', 'Delete'));
                            $message = htmlspecialchars(Yii::t('app', 'Do you really want to delete "') . $model['name'] . '"?');

                            return Html::tag('span', '', [
                                'class' => 'js-delete uf-listing-button uf-listing-button-delete glyphicon glyphicon-trash',
                                'data-url' => $url,
                                'data-title' => $title,
                                'data-confirm-message' => $message,
                                'data-notification-message' => [
                                    'success' => Yii::t('app', 'Place have been successfully deleted.'),
                                    'error' => Yii::t('app', 'Place have\'t been deleted.'),
                                ],
                            ]);
                        },
                    ],
                    'urlCreator' => function ($action, $model) {
                        switch ($action) {
                            case 'view': return Url::to(['place/view', 'id' => $model['id']]);
                            case 'update': return Url::to(['place/update', 'id' => $model['id']]);
                            case 'delete': return Url::to(['place/delete', 'id' => $model['id']]);
                            default: return Url::to(['place/view', 'id' => $model['id']]);
                        }
                    }
                ],
            ],
        ]); ?>
        <?php \yii\widgets\Pjax::end(); ?>
        </div>
    </div>
</div>