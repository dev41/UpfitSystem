<?php

use app\src\entities\place\Place;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/**
 * @var \yii\web\View $this
 * @var Place $place
 * @var int $clubId
 * @var array $clubs
 */
?>

<div class="js-select-club">
    <?php $form = ActiveForm::begin()?>
    <?= $form->field($place, 'parent_id', ['template' => '{input}'])->widget(Select2::class, [
        'data' => ArrayHelper::map($clubs, 'id', 'name'),
        'options' => [
            'value' => $clubId,
            'multiple' => false,
            'prompt' => \Yii::t('app', 'select club'),
        ],
        'pluginOptions' => [
            'allowClear' => true,
            'tags' => true,
            'tokenSeparators' => [','],
        ],
    ]) ?>
    <?php $form = ActiveForm::end()?>
</div>