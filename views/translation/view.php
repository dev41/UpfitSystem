<?php

use app\src\entities\translate\Translation;
use app\src\library\AccessChecker;
use yii\helpers\Html;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $model Translation */

$this->title = StringHelper::truncate(strip_tags($model->message->message), 50, '...');;
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-6">
            <div class="box uf-view js-view">

                <div class="box-header">
                    <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
                </div>

                <div class="box-body">

                    <?= $this->render('/_templates/confirm-dialog.php') ?>
                    <div class="row">
                        <div class="col-sm-3 col-md-2"><strong><?= Yii::t('app', 'ID') ?></strong></div>
                        <div class="col-sm-9 col-md-10"><?= $model->id ?></div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3 col-md-2"><strong><?= Yii::t('app', 'Language') ?></strong></div>
                        <div class="col-sm-9 col-md-10"><?= $model->language ?></div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3 col-md-2"><strong><?= Yii::t('app', 'Translation') ?></strong></div>
                        <div class="col-sm-9 col-md-10"><?= $model->translation ?></div>
                    </div>


                    <div class="row">
                        <div class="col-xs-12">
                            <?= Html::a(\Yii::t('app', 'Back'), ['/translation/index'],
                                [
                                    'class' => 'btn btn-success float-left mr-3',
                                    'value' => 'save',
                                ]
                            ) ?>

                            <?php if (AccessChecker::hasPermission('translation.update')) {
                                echo Html::a(\Yii::t('app', 'Edit'), ['update', 'id' => $model->id, 'language' => $model->language],
                                    [
                                        'class' => 'btn btn-success float-left',
                                        'value' => 'save',
                                    ]
                                );
                            } ?>

                            <?php if (AccessChecker::hasPermission('translation.delete')) {
                                $title = htmlspecialchars(Yii::t('app', 'Delete translation'));
                                $message = htmlspecialchars(Yii::t('app', 'Do you really want to delete "')
                                    . $model['id'] . Yii::t('app', '" translation?'));
                                echo Html::tag('span', \Yii::t('app', 'Delete'), [
                                    'class' => 'js-delete uf-view-button uf-view-button-delete btn btn-danger float-right',
                                    'data-url' => '/translation/delete?id=' . $model->id . '&language=' . $model->language,
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
