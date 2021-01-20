<?php

use app\src\entities\sale\Sale;
use app\src\library\AccessChecker;
use yii\helpers\Html;
use yii\web\View;
use \app\src\helpers\ImageHelper;

/* @var View $this */
/* @var Sale $sale */

$this->title = Yii::t('app', 'Sale View');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Sale'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$image = $sale->getImage();

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-10">
            <div class="box uf-view js-view">

                <div class="panel-heading ">
                    <strong><?= $sale->name ?></strong>
                </div>

                <div class="box-body">

                    <?= $this->render('/_templates/confirm-dialog.php') ?>

                    <div class="row">
                        <div class="col-sm-3 col-md-2"><strong><?= Yii::t('app', 'Club') ?></strong></div>
                        <div class="col-sm-9 col-md-10"><?= $sale->getClub()->name ?></div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3 col-md-2"><strong><?= Yii::t('app', 'Logo') ?></strong></div>
                        <div class="col-sm-9 col-md-10">
                            <?php if ($image['file_name']): ?>
                                <img class="img-thumbnail" src="<?= ImageHelper::getUrl($sale, $image['file_name']) ?>">
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3 col-md-2"><strong><?= Yii::t('app', 'Description') ?></strong></div>
                        <div class="col-sm-9 col-md-10"><?= $sale->description ?></div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3 col-md-2"><strong><?= Yii::t('app', 'Start') ?></strong></div>
                        <div class="col-sm-9 col-md-10"><?= date('F dS Y, h:m a', strtotime($sale->start)); ?></div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3 col-md-2"><strong><?= Yii::t('app', 'End') ?></strong></div>
                        <div class="col-sm-9 col-md-10"><?= date('F dS Y, h:m a', strtotime($sale->end)); ?></div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3 col-md-2"><strong><?= Yii::t('app', 'Created Date') ?></strong></div>
                        <div class="col-sm-9 col-md-10"><?= $sale->created_at ?></div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12">

                            <?= Html::a(\Yii::t('app', 'Back'), ['/sale/index'],
                                [
                                    'class' => 'btn btn-success float-left mr-3',
                                    'value' => 'save',
                                ]
                            ) ?>

                            <?php if (AccessChecker::hasPermission('sale.update')) {
                                echo Html::a(\Yii::t('app', 'Edit'), ['/sale/update?id=' . $sale->id],
                                    [
                                        'class' => 'btn btn-success float-left',
                                        'value' => 'save',
                                    ]
                                );
                            } ?>

                            <?php if (AccessChecker::hasPermission('sale.delete')) {
                                $title = htmlspecialchars(Yii::t('app', 'Delete sale'));
                                $message = htmlspecialchars(Yii::t('app', 'Do you really want to delete the sale?'));
                                echo Html::tag('span', Yii::t('app', 'Delete'), [
                                    'class' => 'js-delete uf-view-button uf-view-button-delete btn btn-danger float-right',
                                    'data-url' => '/sale/delete?id=' . $sale->id,
                                    'data-title' => $title,
                                    'data-message' => $message,
                                ]);
                            } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>