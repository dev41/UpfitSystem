<?php

use app\src\entities\access\AccessPermission;
use app\src\entities\access\AccessRole;
use app\src\widget\Permission;
use yii\data\ActiveDataProvider;
use yii\web\View;
use yii\widgets\ListView;

/**
 * @var View $this
 * @var AccessRole $role
 * @var ActiveDataProvider $dataProvider
 * @var array $permissionTypes
 * @var string $controlTitle
 * @var string $inputName
 * @var string $widgetHash
 * @var array $controlsWithTypes
 */

/** @var Permission $widget */
$widget = $this->context;
?>

<table class="table table-bordered table-hover js-permission-container">
    <thead>
    <tr>
        <th><?= Yii::t('app', $controlTitle) ?></th>
        <?php foreach ($permissionTypes as $type): ?>
            <?php $typeName = AccessPermission::$typeLabels[$type] ?>
            <th class="select-type">
                <input
                    type="checkbox"
                    id="<?= $widget->getUniqueSlug('select-type', $type) ?>"
                    class="js-permission-type"
                    data-type="<?= $type ?>"
                    <?php if ($role->isNewRecord) : ?> checked="checked" <?php endif; ?>
                />&nbsp;
                <label for="<?= $widget->getUniqueSlug('select-type', $type) ?>">
                    <?= $typeName == 'index' ? Yii::t('app', 'Read') : Yii::t('app', ucwords($typeName)) ?>
                </label>
            </th>
        <?php endforeach; ?>
    </tr>
    </thead>
    <tbody>
    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => 'partial/_permission_list',
        'summary' => '',
        'emptyText' => '',
        'viewParams' => [
            'role' => $role,
            'inputName' => $inputName,
            'permissionTypes' => $permissionTypes,
            'controlsWithTypes' => $controlsWithTypes,
        ],
    ]) ?>
    <tr>
        <td colspan="5" align="right">
            <input type="checkbox" id="<?= $widget->getUniqueSlug('select-all') ?>" class="js-permission-select-all" <?php if ($role->isNewRecord) : ?> checked="checked" <?php endif; ?> />&nbsp;
            <label for="<?= $widget->getUniqueSlug('select-all') ?>"><b><?= Yii::t('app', 'Select All') ?></b></label>
        </td>
    </tr>
    </tbody>
</table>