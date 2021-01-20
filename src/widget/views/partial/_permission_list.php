<?php

use app\src\entities\access\AccessControl;
use app\src\entities\access\AccessRole;
use app\src\widget\Permission;
use yii\helpers\Html;
use yii\web\View;

/**
 * @var View $this
 * @var AccessRole $role
 * @var AccessControl $model
 * @var array $permissionTypes
 * @var array $controlsWithTypes
 * @var string $key
 * @var string $inputName
 */

/** @var Permission $widget */
$widget = $this->context;
?>

<?= Permission::renderParentRow($model); ?>

<tr class="js-permission-control-container">
    <td class="select-control">
        <?php if (count($permissionTypes) > 1): ?>
            <input
                type="checkbox"
                class="js-permission-control"
                id="<?= $widget->getUniqueSlug('select-control', $model->id) ?>"
                data-control-id="<?= $model->id ?>"
                <?php if ($role->isNewRecord): ?> checked="checked" <?php endif; ?>
            />&nbsp;
            <label for="<?= $widget->getUniqueSlug('select-control', $model->id) ?>">
                <?= Html::encode(Yii::t('app', $model->name)) ?>
        </label>
        <?php else: ?>
            <span class="ml-3"><?= Html::encode(Yii::t('app', $model->name)) ?></span>
        <?php endif ?>
    </td>
    <?php foreach ($permissionTypes as $type): ?>
        <?php $fullInputName = $inputName . '[' . $model->id . '][' . $type . ']' ?>
        <?php $roleHasAccess = array_key_exists($model->id, $controlsWithTypes) && in_array($type, $controlsWithTypes[$model->id]) ?>
        <td align="center">
            <input
                type="checkbox"
                name="<?= $fullInputName ?>"
                class="js-permission-control_type"
                <?php if ($role->isNewRecord || $roleHasAccess): ?> checked="checked" <?php endif; ?>
                value="<?= $type ?>"
            />
        </td>
    <?php endforeach; ?>
</tr>