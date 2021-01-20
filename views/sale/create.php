<?php

use app\src\entities\sale\Sale;
use app\src\entities\translate\Language;
use app\src\widget\UFActiveForm;
use yii\helpers\Html;
use yii\web\View;

/* @var View $this */
/* @var Sale $sale */

$this->title = $sale->isNewRecord ? Yii::t('app', 'Sale Create') : Yii::t('app', 'Sale Update');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Sale'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <div class=" uf-form-create">
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

                        <?php $form = UFActiveForm::begin(['id' => 'sale-update-form']) ?>

                        <div class="tab-content js-edit-sale-container">
                            <div class="tab-pane active" id="general">

                                <?= $this->render('/sale/partial/form_body', $_params_ + ['form' => $form]); ?>
                            </div>
                            <div class="tab-pane" id="translate">
                                <?= $this->render('/sale/partial/_translations', [
                                    'form' => $form,
                                    'model' => $sale,
                                    'languages' => Language::getList(),
                                ]) ?>
                            </div>
                            <div class="nav-tabs-buttons">
                                <?= Html::submitButton(
                                    $sale->isNewRecord
                                        ? Yii::t('app', 'Create Sale')
                                        : Yii::t('app', 'Update Sale'),
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
</div>
