<?php

use app\src\entities\access\AccessRole;
use app\src\entities\place\Club;
use app\src\entities\trigger\Trigger;
use app\src\entities\user\Position;
use app\src\entities\user\User;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\jui\Accordion;
use \app\src\helpers\TriggerHelper;
use \kartik\sortinput\SortableInput;
use \app\src\entities\trigger\TriggerType;

/* @var View $this */
/* @var Trigger $trigger */
/* @var Club[] $clubs */
/* @var Position[] $positions */
/* @var AccessRole[] $roles */
/* @var User[] $users */
/* @var User[] $receivers */

$triggerTypes = $trigger->types;
$trigger->types = null;
$senderEmailClass = TriggerHelper::isTriggerTypeEmailNotification($triggerTypes) ? '' : 'hide';
?>

<?= $form->field($trigger, 'name')->label(\Yii::t('app', 'Name')) ?>

<div class="form-group">

    <div class="col-lg-12 col-lg-offset-0 col-sm-10 col-sm-offset-2 p-0">
        <div class="form-group">

            <div class="col-lg-4 col-lg-offset-2 p-0">
                <div class="row">
                    <div class="col-lg-12">
                        <label><?= Yii::t('app', 'Picked Types') ?></label>
                    </div>
                </div>
                <?= $form->field($trigger, 'types', ['template' => '<div class="col-lg-12 col-xs-12 p-0">{input}{error}{hint}</div>'])
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
                        'items' => $trigger->getTypes()
                            ? TriggerHelper::getTypeTemplates($triggerTypes)
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
                            'items' => $trigger->isNewRecord ? TriggerHelper::getTypeTemplates() : TriggerHelper::getTypeTemplates($trigger->getTypes(), true)
                        ]); ?>
                    </div>
                </div>
            </div>

        </div>
        <small class="col-lg-10 col-lg-offset-2 p-0 mb-2 text-muted">
            <?= Yii::t('app', 'messages will be sent in the specified order') ?>
        </small>
    </div>
</div>

<?= $form->field($trigger, 'event')->widget(Select2::class, [
    'data' => Trigger::getTranslateEventLabels(),
    'options' => [
        'class' => 'js-event-select',
        'data-codes' => json_encode(Trigger::EVENT_CODES),
        'data-default-codes' => json_encode(Trigger::DEFAULT_CODES),
        'multiple' => false,
        'prompt' => \Yii::t('app', 'select trigger'),
    ],
    'pluginOptions' => [
        'tags' => true,
        'tokenSeparators' => [','],
    ],
])->label(\Yii::t('app', 'Event')) ?>

<?= $form->field($trigger, 'clubsIds', ['options' => ['class' => 'hide']])->widget(Select2::class, [
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

<?= $form->field($trigger, 'title')->label(\Yii::t('app', 'Title')) ?>

<?php $header = Html::tag('div', '', ['class' => 'pull-left trigger-advanced-filters']) . '&nbsp; ' . Yii::t('app', 'Advanced Filters') . ': ';

$content = $form->field($trigger, 'advanced_filters')->checkbox(['class' => 'js-checkbox-advanced_filters']) . '<div class="js-advanced_filters-accordion">'
    . $form->field($trigger, 'sender_email', ['options' => ['class' => 'js-sender-email ' . $senderEmailClass]])->label(\Yii::t('app', 'Sender email'))
    . $form->field($trigger, 'places')->widget(Select2::class, [
        'data' => ArrayHelper::map($clubs, 'id', 'name'),
        'options' => [
            'multiple' => true,
            'prompt' => \Yii::t('app', 'select clubs'),
        ],
        'pluginOptions' => [
            'tags' => true,
            'tokenSeparators' => [','],
        ],
    ])->label(\Yii::t('app', 'Clubs'))

    . $form->field($trigger, 'to_user_type')->widget(Select2::class, [
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
    ])->label(\Yii::t('app', 'User type'))

    . $form->field($trigger, 'positions')->widget(Select2::class, [
        'data' => ArrayHelper::map($positions, 'id', 'name'),
        'options' => [
            'multiple' => true,
            'prompt' => \Yii::t('app', 'select positions'),
        ],
        'pluginOptions' => [
            'tags' => true,
            'tokenSeparators' => [','],
        ],
    ])->label(\Yii::t('app', 'Positions'))

    . $form->field($trigger, 'roles')->widget(Select2::class, [
        'data' => ArrayHelper::map($roles, 'id', 'name'),
        'options' => [
            'multiple' => true,
            'prompt' => \Yii::t('app', 'select roles'),
        ],
        'pluginOptions' => [
            'tags' => true,
            'tokenSeparators' => [','],
        ],
    ])->label(\Yii::t('app', 'Roles'))

    . $form->field($trigger, 'users')->widget(Select2::class, [
        'data' => ArrayHelper::map($users, 'id', 'username'),
        'options' => [
            'multiple' => true,
            'prompt' => \Yii::t('app', 'select users'),
        ],
        'pluginOptions' => [
            'tags' => true,
            'tokenSeparators' => [','],
        ],
    ])->label(\Yii::t('app', 'Users')) . '</div>';

$items[] = [
    'header' => $header,
    'content' => $content,
]; ?>

<?= $form->field($trigger, 'template', [
    'options' => ['class' => 'form-group js-template mt-3'],
    'template' => '<div class="col-sm-2 p-0">{label}</div>
                           <div class="col-sm-10 p-0"><div class="js-template-hint event-codes"></div>{input}{error}</div>',
])->textarea(['rows' => 5])
    ->label(\Yii::t('app', 'Template')) ?>

<div class="trigger-advanced-filters">
<?= Accordion::widget([
    'items' => $items,
    'clientOptions' => [
        'heightStyle' => 'content',
        'animate' => ['duration' => 300],
        'collapsible' => true,
        'active' => false,
    ],
    'headerOptions' => ['tag' => 'div'],
]); ?>
</div>
