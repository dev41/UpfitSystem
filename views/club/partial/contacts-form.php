<?php

use app\src\entities\place\Club;
use app\src\widget\UFActiveForm;
use yii\helpers\Html;
use yii\web\View;
use \app\src\helpers\InputHelper;

/* @var View $this */
/* @var Club $club */

$this->registerJsFile('https://maps.googleapis.com/maps/api/js?key=AIzaSyBUsOw2fmkE2GVIQVLf_vysUlynbyolNLE&libraries=places');
?>

<div class="uf-form-create">
    <div class="box-body">
        <div class="row">
            <div class="col-lg-6">
                <?php $form = UFActiveForm::begin(['id' => 'club-update-contacts-form', 'options' => ['class' => 'js-club-update-contacts-form']]) ?>

                <?= $form->field($club, 'phone_number')->label(\Yii::t('app', 'Phone Number')) ?>
                <?= $form->textInput($club, 'email', ['class' => 'form-control'], InputHelper::TYPE_EMAIL)->label(\Yii::t('app', 'Email')) ?>
                <?= $form->field($club, 'site')->input('url')->label(\Yii::t('app', 'Site')) ?>
                <?= $form->textInput($club, 'facebook_id', ['class' => 'form-control'])->label(\Yii::t('app', 'Facebook id')) ?>
                <?= $form->field($club, 'instagram_id')->label(\Yii::t('app', 'Instagram id')) ?>
                <?= $form->field($club, 'country')->textInput(['class' => 'js-country map-input form-control'])->label(\Yii::t('app', 'Country')) ?>
                <?= $form->field($club, 'city')->textInput(['class' => 'js-city map-input form-control'])->label(\Yii::t('app', 'City')) ?>
                <?= $form->field($club, 'address')->textInput(['class' => 'js-address map-input form-control'])->label(\Yii::t('app', 'Address')) ?>
                <?= $form->field($club, 'lat')->textInput(['class' => 'js-lat map-input readonly form-control', 'readonly' => true])->label(\Yii::t('app', 'Lat')) ?>
                <?= $form->field($club, 'lng')->textInput(['class' => 'js-lng map-input readonly form-control', 'readonly' => true])->label(\Yii::t('app', 'Lng')) ?>
            </div>
            <div class="col-lg-6">
                <input class="controls js-pac-input pac-input" type="text" placeholder="Enter a location">
                <div class="js-map map"></div>
            </div>
        </div>

        <div>

            <?= Html::submitButton(\Yii::t('app', 'Update Contacts'), [
                'class' => 'js-button-process-contacts btn btn-success float-right',
                'data-url' => 'update-info',
                'data-form-name' => '.js-club-update-contacts-form',
                'data-message' => [
                            'success' => Yii::t('app', 'Club contacts was saved.'),
                            'error' => Yii::t('app', 'Club contacts was\'t save!')
                        ]
            ]) ?>
        </div>

        <?php UFActiveForm::end() ?>

    </div>
</div>