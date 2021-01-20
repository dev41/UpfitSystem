<?php

use app\src\entities\news\News;
use app\src\entities\translate\Language;
use app\src\widget\UFActiveForm;
use yii\helpers\Html;
use yii\web\View;

/* @var View $this */
/* @var News $news */

$this->title = $news->isNewRecord ? Yii::t('app', 'News Create') : Yii::t('app', 'News Update');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <div class="uf-form-create">
                <div class="nav-tabs-form js-nav-tabs">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#general" data-toggle="tab" aria-expanded="false"><?= $this->title ?></a>
                        </li>
                        <li>
                            <a href="#translate" data-toggle="tab"
                               aria-expanded="false"><?= \Yii::t('app', 'Translation') ?></a>
                        </li>
                    </ul>

                    <?php $form = UFActiveForm::begin(['id' => 'news-update-form']) ?>

                    <div class="box-body">
                        <div class="tab-content js-edit-news-container">
                            <div class="tab-pane active" id="general">
                                <?= $this->render('/news/partial/form_body', $_params_ + ['form' => $form]); ?>
                            </div>
                            <div class="tab-pane" id="translate">
                                <?= $this->render('/news/partial/_translations', [
                                    'form' => $form,
                                    'model' => $news,
                                    'languages' => Language::getList(),
                                ]) ?>
                            </div>
                            <div class="nav-tabs-buttons">
                                <?= Html::submitButton(
                                    $news->isNewRecord ? \Yii::t('app', 'Create News')
                                        : \Yii::t('app', 'Update News'),
                                    [
                                        'class' => 'js-button-process btn btn-success float-right',
                                        'autofocus' => 1,
                                    ]
                                ) ?>
                            </div>
                        </div>
                    </div>
                    <?php UFActiveForm::end() ?>
                </div>
            </div>
        </div>
    </div>
</div>
