<?php

use app\src\entities\coaching\Event;
use app\src\entities\user\User;
use app\src\entities\user\UserEvent;
use kartik\datecontrol\DateControl;
use yii\web\View;
use yii\widgets\ActiveForm;

/**
 * @var View $this
 * @var ActiveForm $form
 * @var Event $event
 * @var UserEvent $usersEvent
 * @var User[] $users
 */
?>

<?= $form->field($event, 'start')->widget(DateControl::class, [
    'type' => DateControl::FORMAT_DATETIME,
    'options' => [
        'placeholder' => 'Select start',
    ],
    'autoWidget' => true,
])->label(\Yii::t('app', 'start')); ?>

<?= $form->field($event, 'end')->widget(DateControl::class, [
    'type' => DateControl::FORMAT_DATETIME,
    'options' => ['placeholder' => 'Select start'],
])->label(\Yii::t('app', 'end')); ?>