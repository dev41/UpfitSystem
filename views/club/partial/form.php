<?php

use app\src\entities\place\Club;
use app\src\entities\user\User;
use app\src\library\AccessChecker;
use app\src\widget\UFActiveForm;
use yii\helpers\Html;
use yii\web\View;

/* @var View $this */
/* @var Club $club */
/* @var User[] $owners */

?>

<div class="box uf-form-create">

    <div class="box-header">
        <h3 class="box-title"><?= $this->title ?></h3>
    </div>

    <div class="box-body">
        <?php $form = UFActiveForm::begin(['id' => 'club-update-form', 'options' => ['class' => 'js-club-update-form']]) ?>

        <?= $form->field($club, 'id', ['template' => '{input}'])->hiddenInput()->label(false) ?>

        <?= $form->field($club, 'name')->label(\Yii::t('app', 'Name')) ?>

        <?= $form->field($club, 'deleteImages[]', ['template' => '{input}'])->hiddenInput()->label(false) ?>
        <?= $form->field($club, 'deleteLogo[]', ['template' => '{input}'])->hiddenInput()->label(false) ?>

        <?= \app\src\helpers\FormHelper::getImagesField($form, $club, [
            'extPath' => '/logo'
        ]); ?>

        <?= \app\src\helpers\FormHelper::getImagesField($form, $club, [
            'label' => 'Image',
            'jsClass' => 'js-images',
            'fieldName' => 'images[]',
            'widgetConfig' => [
                'options' => ['multiple' => true],
            ],
            'extPath' => '',
            'field' => $club->images
        ]); ?>

        <?= $form->field($club, 'description')->label(\Yii::t('app', 'Description'))->textarea(['rows' => 4]) ?>

        <?= $form->field($club, 'country')->textInput(['class' => 'js-country map-input'])->label(\Yii::t('app', 'Country')) ?>
        <?= $form->field($club, 'city')->textInput(['class' => 'js-city map-input'])->label(\Yii::t('app', 'City')) ?>
        <?= $form->field($club, 'address')->textInput(['class' => 'js-address map-input'])->label(\Yii::t('app', 'Address')) ?>
        <?= $form->field($club, 'lat')->textInput(['class' => 'js-lat map-input readonly', 'readonly' => true])->label(\Yii::t('app', 'Lat')) ?>
        <?= $form->field($club, 'lng')->textInput(['class' => 'js-lng map-input readonly', 'readonly' => true])->label(\Yii::t('app', 'Lng')) ?>

        <div class="row">
            <div class="col-md-12">
                <input class="controls js-pac-input pac-input" type="text" placeholder="Enter a location">
                <div class="js-map map"></div>
            </div>
        </div>

        <div>

            <?= Html::submitButton(\Yii::t('app', $club->isNewRecord ? 'Create club' : 'Update Club'), [
                'class' => 'js-button-process btn btn-success float-right',
            ]) ?>

            <?php if (AccessChecker::hasPermission('schedule.index')) {
                echo Html::button(\Yii::t('app', 'Schedule'), [
                    'class' => 'js-button-schedule btn btn-warning float-right mr-2'
                ]);
            } ?>
        </div>

        <?php UFActiveForm::end() ?>

    </div>
</div>