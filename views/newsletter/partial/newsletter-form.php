<?php

use app\src\entities\access\AccessRole;
use app\src\entities\place\Club;
use app\src\entities\trigger\Newsletter;
use app\src\entities\user\Position;
use app\src\entities\user\User;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\web\View;
use \app\src\helpers\TriggerHelper;
use \kartik\sortinput\SortableInput;
use \app\src\entities\trigger\TriggerType;
use app\src\entities\trigger\Trigger;

/* @var View $this */
/* @var Newsletter $newsletter */
/* @var Club[] $clubs */
/* @var Position[] $positions */
/* @var AccessRole[] $roles */
/* @var User[] $users */
/* @var User[] $receivers */

$newsletterTypes = $newsletter->types;
$newsletter->types = null;
$senderEmailClass = TriggerHelper::isTriggerTypeEmailNotification($newsletterTypes) ? '' : 'hide';
?>


<?= $form->field($newsletter, 'name')->label(\Yii::t('app', 'Name')) ?>

    <div class="form-group">

        <div class="col-lg-12 col-lg-offset-0 col-sm-10 col-sm-offset-2 p-0">
            <div class="form-group">

                <div class="col-lg-4 col-lg-offset-2 p-0">
                    <div class="row">
                        <div class="col-lg-12">
                            <label><?= Yii::t('app', 'Picked Types') ?></label>
                        </div>
                    </div>
                    <?= $form->field($newsletter, 'types', ['template' => '<div class="col-lg-12 col-xs-12 p-0">{input}{error}{hint}</div>'])
                        ->widget(SortableInput::class, [
                            'id' => 'trigger-type-connected',
                            'hideInput' => true,
                            'sortableOptions' => [
                                'connected' => true,
                            ],
                            'options' => [
                                'class' => 'form-control trigger-type-sortable-input js-trigger-type-sortable-input',
                                'readonly' => true,
                            ],
                            'items' => $newsletter->getTypes()
                                ? TriggerHelper::getTypeTemplates($newsletterTypes)
                                : TriggerHelper::getTypeTemplates([new TriggerType(['type' => Trigger::TYPE_MOBILE_NOTIFICATION])])
                        ])->label(false); ?>
                    <?= '<div class="clearfix"></div>' ?>
                </div>

                <div class="col-lg-4 col-lg-offset-1 p-0">
                    <div class="row">
                        <div class="col-lg-12">
                            <label><?= Yii::t('app', 'Available Types') ?></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-sm-12">
                            <?= SortableInput::widget([
                                'id' => 'trigger-type',
                                'name' => 'trigger-type',
                                'hideInput' => true,
                                'sortableOptions' => [
                                    'connected' => true,
                                ],
                                'options' => ['class' => 'form-control trigger-type-sortable-input js-trigger-type-sortable-input-available', 'readonly' => true],
                                'items' => $newsletter->isNewRecord
                                    ? TriggerHelper::getTypeTemplates()
                                    : TriggerHelper::getTypeTemplates($newsletter->getTypes(), true)
                            ]); ?>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>

<?= $form->field($newsletter, 'title')->label(\Yii::t('app', 'Title')) ?>

<?= $form->field($newsletter, 'clubsIds', ['options' => ['class' => 'hide']])->widget(Select2::class, [
    'data' => ArrayHelper::map($clubs, 'id', 'name'),
    'options' => [
        'multiple' => true,
        'prompt' => \Yii::t('app', 'select clubs'),
    ],
    'pluginOptions' => [
        'tags' => true,
        'tokenSeparators' => [','],
    ],
])->label(\Yii::t('app', 'Clubs')) ?>

<?= $form->field($newsletter, 'places', ['template' => '<div class="col-sm-2 col-xs-12 p-0 mb-1">{label}</div><div class="col-sm-10 col-xs-12 p-0 mb-1">{input}{error}{hint}</div>'])
    ->widget(Select2::class, [
    'data' => ArrayHelper::map($clubs, 'id', 'name'),
    'options' => [
        'multiple' => true,
        'prompt' => \Yii::t('app', 'select clubs'),
    ],
    'pluginOptions' => [
        'tags' => true,
        'tokenSeparators' => [','],
    ],
])->label(\Yii::t('app', 'Clubs for filters'), ['class' => 'mb-0']) ?>

<?= $form->field($newsletter, 'to_user_type')->widget(Select2::class, [
    'data' => [
        'to_staff' => Yii::t('app', 'to staff'),
        'to_customers' => Yii::t('app', 'to customers'),
    ],
    'options' => [
        'multiple' => true,
        'prompt' => \Yii::t('app', 'select types'),
    ],
    'pluginOptions' => [
        'tags' => true,
        'tokenSeparators' => [','],
    ],
])->label(\Yii::t('app', 'User type')) ?>

<?= $form->field($newsletter, 'positions')->widget(Select2::class, [
    'data' => ArrayHelper::map($positions, 'id', 'name'),
    'options' => [
        'multiple' => true,
        'prompt' => \Yii::t('app', 'select positions'),
    ],
    'pluginOptions' => [
        'tags' => true,
        'tokenSeparators' => [','],
    ],
])->label(\Yii::t('app', 'Positions')) ?>

<?= $form->field($newsletter, 'roles')->widget(Select2::class, [
    'data' => ArrayHelper::map($roles, 'id', 'name'),
    'options' => [
        'multiple' => true,
        'prompt' => \Yii::t('app', 'select roles'),
    ],
    'pluginOptions' => [
        'tags' => true,
        'tokenSeparators' => [','],
    ],
])->label(\Yii::t('app', 'Roles')) ?>

<?= $form->field($newsletter, 'users')->widget(Select2::class, [
    'data' => ArrayHelper::map($users, 'id', 'username'),
    'options' => [
        'multiple' => true,
        'prompt' => \Yii::t('app', 'select users'),
    ],
    'pluginOptions' => [
        'tags' => true,
        'tokenSeparators' => [','],
    ],
])->label(\Yii::t('app', 'Users')) ?>

<?= $form->field($newsletter, 'template', [
    'options' => ['class' => 'js-template mt-3'],
    'template' => '<div class="col-sm-2 p-0">{label}</div>
                           <div class="col-sm-10 p-0"><div class="js-template-hint event-codes"></div>{input}{error}</div>',
])->textarea(['rows' => 5])
    ->label(\Yii::t('app', 'Template')) ?>