<?php

use app\src\entities\access\AccessPermission;
use app\src\entities\access\AccessRole;
use app\src\entities\place\Club;
use app\src\widget\Permission;
use app\src\widget\UFActiveForm;
use kartik\select2\Select2;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;

/**
 * @var View $this
 * @var AccessRole $role
 * @var array $controlsWithTypes
 * @var ActiveDataProvider $accessToControllersDataProvider
 * @var ActiveDataProvider $accessToActionsDataProvider
 */
$this->title = $role->isNewRecord ? \Yii::t('app', 'Role Create') : \Yii::t('app', 'Role Update');
$this->params['breadcrumbs'][] = ['label' => \Yii::t('app', 'Role'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="box uf-form-create">

    <?php if ($this->title): ?>
        <div class="box-header">
            <h3 class="box-title"><?= $this->title ?></h3>
        </div>
    <?php endif; ?>

    <div class="box-body">

        <?php $form = UFActiveForm::begin(['id' => 'role-update-form']) ?>

        <?= $form->field($role, 'name')->label(\Yii::t('app', 'Name')) ?>

        <?= $form->field($role, 'slug')->label(\Yii::t('app', 'Slug')) ?>

        <?= $form->field($role, 'clubs')->widget(Select2::class, [
            'data' => ArrayHelper::map(Club::getClubsByUserId(), 'id', 'name'),
            'options' => [
                'multiple' => true,
                'prompt' => \Yii::t('app', 'select clubs'),
            ],
            'pluginOptions' => [
                'tags' => true,
                'tokenSeparators' => [','],
            ],
        ])->label(\Yii::t('app', 'Clubs')) ?>

        <label for="permission-table"><?= Yii::t('app', 'Permission') ?></label>

        <div class="row">

            <div>
                <div class="col-md-6 col-sm-6">
                    <?= Permission::widget([
                        'role' => $role,
                        'controlTitle' => \Yii::t('app', 'Controller name'),
                        'inputName' => 'Permissions',
                        'dataProvider' => $accessToControllersDataProvider,
                        'permissionTypes' => [
                            AccessPermission::TYPE_READ,
                            AccessPermission::TYPE_CREATE,
                            AccessPermission::TYPE_UPDATE,
                            AccessPermission::TYPE_DELETE,
                        ]
                    ]) ?>
                </div>
            </div>

            <div>
                <div class="col-md-6 col-sm-6">
                    <?= Permission::widget([
                        'role' => $role,
                        'controlTitle' => \Yii::t('app', 'Action'),
                        'inputName' => 'Permissions',
                        'dataProvider' => $accessToActionsDataProvider,
                        'permissionTypes' => [AccessPermission::TYPE_CUSTOM]
                    ]) ?>
                </div>
            </div>

        </div>

        <?= Html::submitButton($role->isNewRecord
            ? \Yii::t('app', 'Create Role')
            : \Yii::t('app', 'Update Role'),
            [
                'class' => 'js-button-process btn btn-success float-right',
                'autofocus' => 1,
            ]) ?>

        <?php UFActiveForm::end() ?>

    </div>
</div>
