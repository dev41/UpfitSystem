<?php

use app\src\entities\news\News;
use app\src\library\AccessChecker;
use yii\helpers\Html;
use yii\web\View;
use \app\src\helpers\ImageHelper;

/* @var View $this */
/* @var News $news */

$this->title = Yii::t('app', 'News View');
$activeClass = $news->active ?
    $activeClass = 'glyphicon glyphicon-ok-circle text-success' :
    $activeClass = 'glyphicon glyphicon-remove text-danger';
$this->params['breadcrumbs'][] = ['label' => \Yii::t('app', 'News'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$image = $news->getImage();

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-10">
            <div class="box uf-view js-view">

                <div class="box-header">
                    <h3 class="box-title"><?= $news->name ?></h3>
                    <span class="active-panel float-right <?= $activeClass ?>"></span>
                </div>

                <div class="box-body">

                    <?= $this->render('/_templates/confirm-dialog.php') ?>

                    <div class="row">
                        <div class="col-sm-3 col-md-2"><strong><?= Yii::t('app', 'Club') ?></strong></div>
                        <div class="col-sm-9 col-md-10"><?= $news->getClub()->name ?></div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3 col-md-2"><strong><?= Yii::t('app', 'Logo') ?></strong></div>
                        <div class="col-sm-9 col-md-10">
                            <?php if ($image['file_name']): ?>
                            <img class="img-thumbnail" src="<?= ImageHelper::getUrl($news, $image['file_name']) ?>">
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3 col-md-2"><strong><?= Yii::t('app', 'Text') ?></strong></div>
                        <div class="col-sm-9 col-md-10"><?= $news->description ?></div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3 col-md-2"><strong><?= Yii::t('app', 'Date') ?></strong></div>
                        <div class="col-sm-9 col-md-10"><?= $news->created_at ?></div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12">

                            <?= Html::a(\Yii::t('app', 'Back'), ['/news/index'],
                                [
                                    'class' => 'btn btn-success float-left mr-3',
                                    'value' => 'save',
                                ]
                            ) ?>

                            <?php if (AccessChecker::hasPermission('news.update')) {
                                echo Html::a(\Yii::t('app', 'Edit'), ['/news/update?id=' . $news->id],
                                    [
                                        'class' => 'btn btn-success float-left',
                                        'value' => 'save',
                                    ]
                                );
                            } ?>

                            <?php if (AccessChecker::hasPermission('news.delete')) {
                                $title = htmlspecialchars(Yii::t('app', 'Delete news'));
                                $message = htmlspecialchars(Yii::t('app', 'Do you really want to delete news?'));
                                echo Html::tag('span', Yii::t('app', 'Delete'), [
                                    'class' => 'js-delete uf-view-button uf-view-button-delete btn btn-danger float-right',
                                    'data-url' => '/news/delete?id=' . $news->id,
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