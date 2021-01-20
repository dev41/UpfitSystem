<?php

use app\src\entities\access\AccessRole;
use app\src\entities\staff\Staff;
use app\src\widget\UFActiveForm;
use kartik\datecontrol\DateControl;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;

/**
 * @var View $this
 * @var Staff $staff
 * @var bool $renderAjax
 * @var AccessRole[] $roles
 * @var array $positions
 * @var array $typeOptions
 */

$renderAjax = $renderAjax ?? false;
?>
<div class="uf-form-create <?= $renderAjax ? 'dialog-size' : '' ?>">
    <div class="box-body">

        <?php $form = UFActiveForm::begin(['id' => 'staff-update-form']) ?>

        <?= $form->field($staff, 'id', ['template' => '{input}'])->hiddenInput()->label(false) ?>
        <?= $form->field($staff, 'clubId', [
            'template' => '{input}',
            'options' => ['class' => 'js-club_id'],
        ])->hiddenInput()->label(false) ?>

        <?= $form->field($staff, 'username')->label(\Yii::t('app', 'Username')) ?>

        <?= $form->field($staff, 'first_name')->label(\Yii::t('app', 'First Name')) ?>

        <?= $form->field($staff, 'last_name')->label(\Yii::t('app', 'Last Name')) ?>

        <?= $form->field($staff, 'role_id')->widget(Select2::class, [
            'data' => ArrayHelper::map($roles, 'id', 'name'),
            'options' => [
                'multiple' => false,
                'prompt' => \Yii::t('app', 'select role'),
            ],
            'pluginOptions' => [
                'tags' => true,
                'tokenSeparators' => [','],
            ],
        ])->label(\Yii::t('app', 'Role')) ?>

        <?php if ($staff->clubId) {
            echo $form->field($staff, 'positions')->widget(Select2::class, [
                'data' => ArrayHelper::map($positions, 'id', 'name'),
                'options' => [
                    'id' => 'staff_positions',
                    'multiple' => true,
                    'prompt' => \Yii::t('app', 'select positions'),
                ],
                'pluginOptions' => [
                    'tags' => true,
                    'tokenSeparators' => [','],
                ],
            ])->label(\Yii::t('app', 'Positions'));
        } ?>

        <?= $form->field($staff, 'email')->label(\Yii::t('app', 'Email')) ?>

        <?= $form->field($staff, 'phone')->label(\Yii::t('app', 'Phone')) ?>

        <?= $form->field($staff, 'birthday')->label(\Yii::t('app', 'Birthday'))->widget(DateControl::class, [
            'type' => DateControl::FORMAT_DATE,
            'options' => ['placeholder' => 'Select created_at date'],
            'autoWidget' => true,
        ]) ?>

        <?= $form->field($staff, 'description')->label(\Yii::t('app', 'Description'))->textarea(['rows' => 4]) ?>

        <?php if ($staff->isNewRecord): ?>
            <?= $form->field($staff, 'password')->passwordInput()->label(\Yii::t('app', 'Password')) ?>
            <?= $form->field($staff, 'confirm_password')->passwordInput()->label(\Yii::t('app', 'Confirm Password')) ?>
        <?php endif; ?>

        <?php if (!$staff->isNewRecord): ?>

            <?= $form->field($staff, 'deleteImages[]', ['template' => '{input}'])->hiddenInput()->label(false) ?>
            <?= $form->field($staff, 'deleteLogo[]', ['template' => '{input}'])->hiddenInput()->label(false) ?>

            <div class="nav-tabs-form js-nav-tabs">
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a href="#avatar-list" data-toggle="tab"
                           aria-expanded="false"><?= \Yii::t('app', 'Avatar') ?></a>
                    </li>
                    <li>
                        <a href="#photos-list" data-toggle="tab"
                           aria-expanded="false"><?= \Yii::t('app', 'Photos') ?></a>
                    </li>
                </ul>

                <div class="tab-content js-edit-photo-container">
                    <div class="tab-pane active" id="avatar-list">
                        <?= \app\src\helpers\FormHelper::getImagesField($form, $staff, [
                            'extPath' => '/logo',
                            'label' => false,
                            'template' => '<div class="col-xs-12">{input}{error}</div>'
                        ]) ?>
                    </div>
                    <div class="tab-pane" id="photos-list">
                        <?= \app\src\helpers\FormHelper::getImagesField($form, $staff, [
                            'label' => false,
                            'jsClass' => 'js-images',
                            'fieldName' => 'images[]',
                            'widgetConfig' => [
                                'options' => ['multiple' => true],
                            ],
                            'extPath' => '',
                            'field' => $staff->images,
                            'template' => '<div class="col-xs-12">{input}{error}</div>'
                        ]) ?>
                    </div>
                </div>
            </div>

        <?php endif; ?>

        <div>

            <?= Html::submitButton(
                $staff->isNewRecord ? \Yii::t('app', 'Create Staff')
                    : \Yii::t('app', 'Update staff'), [
                'class' => 'js-button-process btn btn-success float-right',
            ]) ?>

            <?php if ($renderAjax) {
                echo Html::button(
                    \Yii::t('app', 'Cancel'),
                    [
                        'class' => 'js-button-cancel btn btn-secondary float-right mr-3',
                        'autofocus' => 1,
                    ]
                );
            } ?>

            <?php UFActiveForm::end() ?>
        </div>
    </div>
</div>
