<?php

use app\src\entities\coaching\Coaching;
use app\src\entities\coaching\CoachingLevel;
use app\src\entities\coaching\Event;
use app\src\entities\place\Club;
use app\src\entities\place\Place;
use app\src\entities\user\User;
use app\src\entities\translate\Language;
use app\src\library\AccessChecker;
use app\src\widget\UFActiveForm;
use yii\helpers\Html;
use yii\web\View;

/**
 * @var View $this
 * @var string $action
 * @var Coaching $coaching
 * @var Event $event
 * @var User[] $trainers
 * @var Club[] $clubs
 * @var Place[] $places
 * @var CoachingLevel[] $levels
 */

$processButtonTitle = $coaching->isNewRecord
    ? \Yii::t('app', 'Create Coaching')
    : \Yii::t('app', 'Update Coaching');
$action = $action ?? '';

$this->title = $coaching->isNewRecord
    ? \Yii::t('app', 'Coaching Create')
    : \Yii::t('app', 'Coaching Update');

$this->params['breadcrumbs'][] = ['label' => \Yii::t('app', 'Coaching'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <div class="uf-form-create" data-coaching='<?= json_encode($coaching->getAttributes()); ?>'>

                <div class="nav-tabs-form js-nav-tabs">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#general" data-toggle="tab" aria-expanded="false"><?= $this->title ?></a>
                        </li>
                        <li>
                            <a href="#translate" data-toggle="tab"
                               aria-expanded="false"><?= \Yii::t('app', 'Translation') ?></a>
                        </li>
                    </ul>

                    <?php $form = UFActiveForm::begin(['id' => 'coaching-update-form', 'action' => $action]) ?>
                    <div class="box-body">
                        <div class="tab-content js-edit-coaching-container">
                            <div class="tab-pane active" id="general">

                                <?= $this->render('/coaching/partial/form_body', $_params_ + ['form' => $form]); ?>

                            </div>
                            <div class="tab-pane" id="translate">
                                <?= $this->render('/coaching/partial/_translations', [
                                    'form' => $form,
                                    'model' => $coaching,
                                    'languages' => Language::getList(),
                                ]) ?>
                            </div>

                            <div class="nav-tabs-buttons">
                                <?= Html::submitButton($processButtonTitle, [
                                    'class' => 'js-button-process btn btn-success float-right',
                                    'autofocus' => 1,
                                ]) ?>

                                <?php if (!$coaching->isNewRecord && AccessChecker::hasPermission('schedule.index')): ?>
                                    <?= Html::button(\Yii::t('app', 'Events'), [
                                        'class' => 'js-button-schedule btn btn-warning float-right mr-3',
                                        'autofocus' => 1,
                                        'data-title' => Yii::t('app', 'Schedule')
                                    ]) ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php UFActiveForm::end() ?>
                </div>
            </div>
        </div>
    </div>
</div>