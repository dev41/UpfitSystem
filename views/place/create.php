<?php

use app\src\entities\place\Place;
use app\src\library\AccessChecker;
use app\src\widget\UFActiveForm;
use app\src\entities\translate\Language;
use yii\helpers\Html;
use yii\web\View;

/* @var View $this */
/* @var Place $place */

$this->title = $place->isNewRecord ? \Yii::t('app', 'Place Create') : \Yii::t('app', 'Place Update');
$this->params['breadcrumbs'][] = ['label' => 'Place', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-9">
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

                    <?php $form = UFActiveForm::begin(['id' => 'place-update-form']) ?>
                    <div class="box-body">
                        <div class="tab-content js-edit-place-container">
                            <div class="tab-pane active" id="general">
                                <?= $this->render('/place/partial/form_body', $_params_ + ['form' => $form]); ?>
                            </div>
                            <div class="tab-pane" id="translate">
                                <?= $this->render('/place/partial/_translations', [
                                    'form' => $form,
                                    'model' => $place,
                                    'languages' => Language::getList(),
                                ]) ?>
                            </div>
                            <div class="nav-tabs-buttons">
                                <?= Html::submitButton(
                                    $place->isNewRecord ? \Yii::t('app', 'Create Place')
                                        : \Yii::t('app', 'Update Place'),
                                    [
                                        'class' => 'js-button-process btn btn-success float-right',
                                        'autofocus' => 1,
                                    ]
                                ) ?>
                                <?php if (!$place->isNewRecord && AccessChecker::hasPermission('place.create')): ?>
                                    <?= Html::button(
                                        \Yii::t('app', 'Copy'),
                                        [
                                            'class' => 'js-button-copy btn btn-success float-right mr-3',
                                            'data-url' => '/place/copy-place?id=' . $place->id,
                                            'data-notification-message' => [
                                                'success' => Yii::t('app', 'Place have been successfully copied.'),
                                                'error' => Yii::t('app', 'Place have\'t been copied.'),
                                            ],
                                        ]
                                    ) ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php UFActiveForm::end() ?>
                </div>
            </div>
        </div>
    </div>
</div>
