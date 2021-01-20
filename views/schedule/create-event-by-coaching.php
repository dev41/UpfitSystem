<?php

use app\src\entities\coaching\Coaching;
use app\src\entities\coaching\CoachingLevel;
use app\src\entities\coaching\Event;
use app\src\entities\place\Club;
use app\src\entities\place\Place;
use app\src\entities\translate\Language;
use app\src\entities\user\User;
use app\src\library\AccessChecker;
use app\src\widget\UFActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;

/**
 * @var View $this
 * @var string $action
 * @var Coaching $coaching
 * @var Event $event
 * @var User[] $trainers
 * @var User[] $customers
 * @var Club[] $clubs
 * @var Place[] $places
 * @var CoachingLevel[] $levels
 */
$action = $action ?? '';
?>
<div class="nav-tabs-form js-nav-tabs">
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#general" aria-controls="general" role="tab" data-toggle="tab">
                <?= Yii::t('app', 'General') ?></a></li>
        <li role="presentation"><a href="#event_customers" aria-controls="customers" role="tab" data-toggle="tab">
                <?= Yii::t('app', 'Customers') ?></a></li>
        <li>
            <a href="#translate" data-toggle="tab" aria-expanded="false">
                <?= \Yii::t('app', 'Translation') ?></a></li>
    </ul>

    <div class="uf-form-create uf-event-create">

        <div class="panel-body">
            <?php $form = UFActiveForm::begin([
                'id' => 'event-create-form',
                'action' => $action,
                'options' => [
                    'data-coaching' => json_encode($coaching->toArray()),
                    'data-event' => json_encode($event->toArray()),
                ],
            ]) ?>

            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="general">
                    <?= $this->render('/coaching/partial/form_body', $_params_ + ['form' => $form]); ?>
                </div>

                <div role="tabpanel" class="tab-pane" id="event_customers">

                    <div class="progress">
                        <div class="js-customer-progress progress-bar progress-bar-success"
                             role="progressbar"
                             aria-valuenow="40"
                             aria-valuemin="0"
                             aria-valuemax="100"
                             style="width: 0">
                            <span></span>
                        </div>
                    </div>

                    <?= $form->field($event, 'users', [
                        'template' => '<div class="col-xs-12">{input}{error}{hint}</div>'
                    ])->widget(Select2::class, [
                        'data' => ArrayHelper::map($customers, 'id', 'username'),
                        'options' => [
                            'multiple' => true,
                            'class' => 'js-customer-select',
                            'prompt' => \Yii::t('app', 'select users'),
                        ],
                        'pluginOptions' => [
                            'tags' => true,
                            'tokenSeparators' => [','],
                        ],
                    ])->label('') ?>
                </div>

                <div class="tab-pane js-schedule-translate" id="translate">
                    <?= $this->render('/schedule/partial/_translations',$_params_ + [
                        'form' => $form,
                        'model' => $coaching,
                        'languages' => Language::getList(),
                    ]) ?>
                </div>
            </div>

            <div class="uf-form-footer">

                <?php if (AccessChecker::hasPermission('schedule.delete') && !$event->isNewRecord) {
                    echo Html::button(
                        \Yii::t('app', 'Delete'), [
                        'class' => 'js-button-delete btn btn-danger float-left',
                        'data-title' => Yii::t('app', 'Event Delete'),
                        'data-message' => Yii::t('app', 'Do you really want to delete'),
                    ]);
                } ?>

                <?= Html::submitButton($event->isNewRecord
                    ? \Yii::t('app', 'Create')
                    : \Yii::t('app', 'Update'),
                    [
                        'class' => 'js-button-process btn btn-success float-right',
                        'autofocus' => 1,
                    ]) ?>

                <?= Html::button(\Yii::t('app', 'Cancel'), [
                    'class' => 'js-button-cancel btn btn-secondary float-right mr-3',
                    'autofocus' => 1,
                ]) ?>

            </div>

            <?php UFActiveForm::end() ?>
        </div>
    </div>
</div>