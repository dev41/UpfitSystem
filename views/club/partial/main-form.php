<?php

use app\src\entities\place\Club;
use app\src\library\AccessChecker;
use app\src\widget\UFActiveForm;
use yii\helpers\Html;
use \app\src\helpers\FormHelper;
use yii\web\View;

/* @var View $this */
/* @var Club $club */
?>

<div class="uf-form-create">

    <div class="box-body">
        <div class="row">
            <div class="col-lg-9">
                <?php $form = UFActiveForm::begin(['id' => 'club-update-info-form', 'options' => ['class' => 'js-club-update-info-form']]) ?>

                <?= $form->field($club, 'id', ['template' => '{input}'])->hiddenInput()->label(false) ?>

                <?= $form->field($club, 'name')->label(\Yii::t('app', 'Name')) ?>

                <?= $form->field($club, 'deleteImages[]', ['template' => '{input}'])->hiddenInput()->label(false) ?>
                <?= $form->field($club, 'deleteLogo[]', ['template' => '{input}'])->hiddenInput()->label(false) ?>

                <?= FormHelper::getImagesField($form, $club, [
                    'extPath' => '/logo'
                ]); ?>

                <?= FormHelper::getImagesField($form, $club, [
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

                <div class="box-footer">

                    <?= Html::submitButton(\Yii::t('app', 'Update Info'), [
                        'class' => 'js-button-process-info btn btn-success float-right',
                        'data-url' => 'update-info',
                        'data-form-name' => '.js-club-update-info-form',
                        'data-message' => [
                            'success' => Yii::t('app', 'Club data was saved.'),
                            'error' => Yii::t('app', 'Club data was\'t save!'),
                            'errorLoadImages' => Yii::t('app', 'Club images was\'t save!'),
                        ]
                    ]) ?>

                    <?php if (AccessChecker::hasPermission('schedule.index')) {
                        echo Html::button(\Yii::t('app', 'Schedule'), [
                            'class' => 'js-button-schedule btn btn-warning float-right mr-2',
                            'data-title' => Yii::t('app', 'Schedule')
                        ]);
                    } ?>
                </div>
                <?php UFActiveForm::end() ?>
            </div>
        </div>
    </div>
</div>