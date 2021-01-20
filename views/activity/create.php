<?php

use app\src\entities\activity\Activity;
use app\src\entities\translate\Language;
use app\src\widget\UFActiveForm;
use yii\helpers\Html;
use yii\web\View;

/* @var View $this */
/* @var Activity $activity */

$this->title = $activity->isNewRecord ? Yii::t('app', 'Activity Create') : Yii::t('app', 'Activity Update');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Activity'), 'url' => ['index']];
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
                    <div class="box-body">

                        <?php $form = UFActiveForm::begin(['id' => 'activity-update-form']) ?>
                        <div class="tab-content js-edit-activity-container">
                            <div class="tab-pane active" id="general">

                                <?= $this->render('/activity/partial/form_body', $_params_ + ['form' => $form]); ?>

                            </div>
                            <div class="tab-pane" id="translate">
                                <?= $this->render('/activity/partial/_translations', [
                                    'form' => $form,
                                    'model' => $activity,
                                    'languages' => Language::getList(),
                                ]) ?>
                            </div>

                            <div class="nav-tabs-buttons">
                                <?= Html::submitButton(
                                    $activity->isNewRecord
                                        ? Yii::t('app', 'Create Activity')
                                        : Yii::t('app', 'Update Activity'),
                                    [
                                        'class' => 'js-button-process btn btn-success float-right',
                                        'autofocus' => 1,
                                    ]
                                ) ?>
                            </div>
                        </div>
                        <?php UFActiveForm::end() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
