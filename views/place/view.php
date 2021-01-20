<?php

use app\src\entities\place\Subplace;
use app\src\library\AccessChecker;
use \app\src\helpers\ImageHelper;
use yii\helpers\Html;
use yii\web\View;

/* @var View $this */
/* @var Subplace $place */
/* @var array $types */

$this->title = Yii::t('app', 'Place View');
$activeClass = $place->active ?
    $activeClass = 'glyphicon glyphicon-ok-circle text-success' :
    $activeClass = 'glyphicon glyphicon-remove text-danger';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Place'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-10">
            <div class="box uf-view js-view">

                <div class="box-header">
                    <h3 class="box-title"><?= $this->title ?></h3>
                    <span class="active-panel float-right <?= $activeClass ?>"></span>
                </div>

                <div class="box-body">

                    <?= $this->render('/_templates/confirm-dialog.php') ?>
                    <div class="row">
                        <div class="col-sm-3 col-md-2"><strong><?= Yii::t('app', 'Name') ?></strong></div>
                        <div class="col-sm-9 col-md-10"><?= $place->name ?></div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3 col-md-2"><strong><?= Yii::t('app', 'Type') ?></strong></div>
                        <div class="col-sm-9 col-md-10"><?= $types[$place->type] ?></div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3 col-md-2"><strong><?= Yii::t('app', 'Club') ?></strong></div>
                        <div class="col-sm-9 col-md-10"><?= $place->parent->name ?></div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3 col-md-2"><strong><?= Yii::t('app', 'Description') ?></strong></div>
                        <div class="col-sm-9 col-md-10"><?= $place->description ?></div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3 col-md-2"><strong><?= Yii::t('app', 'Address') ?></strong></div>
                        <div class="col-sm-9 col-md-10"><?= $place->address ?></div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3 col-md-2"><strong><?= Yii::t('app', 'Images') ?></strong></div>
                        <div class="col-sm-9 col-md-10">
                            <?php foreach ($place->getImages() as $image): ?>
                                <img class="img-thumbnail" src="<?= ImageHelper::getUrl($place, $image->file_name) ?>">
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12">
                            <?= Html::a(\Yii::t('app', 'Back'), ['/place/index'],
                                [
                                    'class' => 'btn btn-success float-left mr-3',
                                    'value' => 'save',
                                ]
                            ) ?>

                            <?php if (AccessChecker::hasPermission('place.update')) {
                                echo Html::a(\Yii::t('app', 'Edit'), ['/place/update?id=' . $place->id],
                                    [
                                        'class' => 'btn btn-success float-left',
                                        'value' => 'save',
                                    ]
                                );} ?>

                            <?php if (AccessChecker::hasPermission('place.delete')) {
                                $title = htmlspecialchars(Yii::t('app', 'Delete place'));
                                $message = htmlspecialchars(Yii::t('app', 'Do you really want to delete "') . $place['name'] . Yii::t('app', '" place?'));
                                echo Html::tag('span', \Yii::t('app', 'Delete'), [
                                    'class' => 'js-delete uf-view-button uf-view-button-delete btn btn-danger float-right',
                                    'data-url' => '/place/delete?id=' . $place->id,
                                    'data-title' => $title,
                                    'data-message' => $message,
                                ]);} ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>