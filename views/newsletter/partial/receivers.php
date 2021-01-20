<?php

use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/* @var [] $receivers */
?>

<div class="uf-view">
    <?php
    $receiversNames = ArrayHelper::map($receivers, 'id', 'name_label');

    echo Select2::widget([
        'name' => 'receivers',
        'data' => $receiversNames,
        'value' => array_keys($receiversNames),
        'options' => [
            'disabled' => true,
            'id' => 'trigger_user_id',
            'multiple' => true,
            'prompt' => '',
        ],
    ]);
    ?>
</div>
