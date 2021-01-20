<?php

use app\src\entities\attribute\Attribute;
use app\src\widget\UFActiveForm;
use yii\web\View;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/**
 * @var View $this
 * @var string $processButtonTitle
 * @var Attribute $attribute
 * @var array $names
 * @var array $types
 */
$processButtonTitle = $processButtonTitle ?? ($attribute->isNewRecord ?
        \Yii::t('app', 'Add Attribute') :
        \Yii::t('app', 'Update Attribute'));

$form = UFActiveForm::begin(['id' => 'attribute-club-form']) ?>

<?php
if (!$attribute->isNewRecord) {
    echo $form->field($attribute, 'id', ['template' => '{input}'])->hiddenInput()->label(false);
}
?>
    <div class="row">

        <div class="col-xs-3">
            <span class="float-left label-attribute-name"><label for="select-names"><?= Yii::t('app', 'Name') ?></label></span>
        </div>

        <div class="col-xs-9">

            <div class="row attribute-name-row">

                <div class="col-xs-9">
                    <?= $form->field($attribute, 'name', ['options' => ['class' => 'row js-select-names', 'id' => 'select-names'],
                        'template' => '<div class="col-xs-12">{input}{error}</div>'
                    ])->widget(Select2::class, [
                        'data' => ArrayHelper::map($names, 'name', 'name'),
                        'options' => [
                            'class' => 'js-select-names',
                            'multiple' => false,
                            'prompt' => \Yii::t('app', 'select name'),
                        ],
                        'pluginOptions' => [
                            'tags' => true,
                            'tokenSeparators' => [','],
                        ],
                    ])->label(false) ?>

                    <?= $form->field($attribute, 'name', ['template' => '<div class="col-xs-12">{input}{error}</div>'])
                        ->textInput(['class' => 'row js-create-attribute-input create-attribute-name-input hidden', 'disabled' => true])->label(false); ?>
                </div>

                <div class="col-xs-3">

                    <?= Html::button(
                        '+',
                        [
                            'class' => 'js-button-create-attribute-name create-attribute-name-btn btn btn-sm btn-success float-right',
                        ]
                    ) ?>
                </div>
            </div>
        </div>
    </div>

<?= $form->field($attribute, 'type')->widget(Select2::class, [
    'data' => $types,
    'options' => [
        'class' => 'js-type-select',
        'multiple' => false,
        'prompt' => \Yii::t('app', 'select type'),
    ],
    'pluginOptions' => [
        'tags' => true,
        'tokenSeparators' => [','],
    ],
])->label(\Yii::t('app', 'Type')) ?>

<?= $form->field($attribute, 'value', [
    'options' => [
        'tag' => 'div',
        'class' => 'row js-value-attribute-string hidden',
        'disabled' => true
    ],
    'template' => '<div class="col-xs-3"></div><div class="col-xs-9">{input}{error}</div>'
])->textInput([
    'type' => 'string'
])->label(false) ?>

<?= $form->field($attribute, 'value', [
    'options' => [
        'tag' => 'div',
        'class' => 'row js-value-attribute-number hidden',
        'disabled' => true
    ],
    'template' => '<div class="col-xs-3"></div><div class="col-xs-9">{input}{error}</div>'
])->textInput([
    'type' => 'number'
])->label(false) ?>

<?= $form->field($attribute, 'value', [
    'options' => [
        'tag' => 'div',
        'class' => 'row js-value-attribute-bool hidden',
        'disabled' => true,
    ]])->checkbox([
    'label' => 'enable',
    'class' => 'js-value-attribute-bool',
]); ?>

<?= Html::button(
    \Yii::t('app', $processButtonTitle),
    [
        'class' => 'js-button-process btn btn-success float-right',
        'autofocus' => 1,
    ]
) ?>

<?= Html::button(
    \Yii::t('app', 'Cancel'),
    [
        'class' => 'js-button-cancel btn btn-secondary float-right mr-3',
        'autofocus' => 1,
    ]
) ?>

<?php UFActiveForm::end() ?>