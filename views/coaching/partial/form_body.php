<?php

use app\src\entities\coaching\Coaching;
use app\src\entities\coaching\CoachingLevel;
use app\src\entities\coaching\Event;
use app\src\entities\place\Club;
use app\src\entities\place\Place;
use app\src\entities\user\User;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\web\View;
use app\src\helpers\FormHelper;

/**
 * @var View $this
 * @var string $title
 * @var string $action
 * @var string $processButtonTitle
 * @var string $cancelButtonTitle
 * @var Coaching $coaching
 * @var Event $event
 * @var User[] $trainers
 * @var Club[] $clubs
 * @var int $clubId
 * @var Place[] $places
 * @var CoachingLevel[] $levels
 */
?>

<?= $form->field($coaching, 'id', ['template' => '{input}'])->hiddenInput()->label(false) ?>

<?= $form->field($coaching, 'name')->label(\Yii::t('app', 'Name')) ?>

<?= $form->field($coaching, 'clubs', ['options' => ['class' => 'row' . (isset($clubId) ? ' hide' : '')]])
    ->widget(Select2::class, [
        'data' => ArrayHelper::map($clubs, 'id', 'name'),
        'options' => [
            'class' => 'js-club-select',
            'multiple' => true,
            'prompt' => \Yii::t('app', 'select clubs'),
        ],
        'pluginOptions' => [
            'tags' => true,
            'tokenSeparators' => [','],
        ],
    ])->label(\Yii::t('app', 'Clubs')) ?>

<?= $form->field($coaching, 'places')->widget(Select2::class, [
    'data' => ArrayHelper::map($places, 'id', 'name'),
    'options' => [
        'class' => 'js-place-select',
        'multiple' => true,
        'prompt' => \Yii::t('app', 'select places'),
    ],
    'pluginOptions' => [
        'tags' => true,
        'tokenSeparators' => [','],
    ],
])->label(\Yii::t('app', 'Places')) ?>

<?php if (isset($event)) {
    echo $this->render('/coaching/partial/event_create', $_params_ + ['form' => $form]);
} ?>

<?= $form->field($coaching, 'coaching_level_id')->widget(Select2::class, [
    'data' => ArrayHelper::map($levels, 'id', 'name'),
    'options' => [
        'multiple' => false,
        'prompt' => \Yii::t('app', 'select level'),
    ],
])->label(\Yii::t('app', 'Level')) ?>

<?= $form->field($coaching, 'description')
    ->textarea(['rows' => 4])
    ->label(\Yii::t('app', 'Description'))
?>

<?= $form->field($coaching, 'price')->label(\Yii::t('app', 'Price')) ?>

<?= $form->field($coaching, 'capacity')
    ->textInput([
        'type' => 'number'
    ])
    ->label(\Yii::t('app', 'Capacity'))
?>

<?php if (!isset($event)) {
    echo $form->field($coaching, 'deleteImages[]', ['template' => '{input}'])->hiddenInput()->label(false);

    echo FormHelper::getImagesField($form, $coaching, [
        'label' => 'Image',
        'jsClass' => 'js-images',
        'fieldName' => 'images[]',
        'extPath' => '',

    ]);
} ?>

    <?= $form->field($coaching, 'color')
    ->widget(\kartik\color\ColorInput::class, [
        'useNative' => false,
        'readonly' => true,
    ])
    ->label(\Yii::t('app', 'Color'));
?>

<?= $form->field($coaching, 'trainers')->widget(Select2::class, [
    'data' => ArrayHelper::map($trainers, 'id', 'username'),
    'options' => [
        'multiple' => true,
        'prompt' => \Yii::t('app', 'select trainers'),
    ],
    'pluginOptions' => [
        'tags' => true,
        'tokenSeparators' => [','],
    ],
])->label(\Yii::t('app', 'Trainers')) ?>