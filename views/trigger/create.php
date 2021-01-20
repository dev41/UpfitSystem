<?php

use app\src\entities\trigger\Trigger;
use app\src\entities\user\User;
use app\src\widget\UFActiveForm;
use yii\web\View;
use app\src\entities\translate\Language;
use yii\helpers\Html;

/* @var View $this */
/* @var Trigger $trigger */
/* @var User[] $receivers */

$this->title = $trigger->isNewRecord ? Yii::t('app', 'Trigger Create') : Yii::t('app', 'Trigger Update');

$this->params['breadcrumbs'][] = ['label' => \Yii::t('app', 'Trigger'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$receivers = $receivers ?? [];
?>

<div class="row">
    <div class="col-lg-8">
        <div class="uf-form-create">
            <div class="nav-tabs-form js-nav-tabs">
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a href="#main" data-toggle="tab" aria-expanded="false"><?= $this->title ?></a>
                    </li>
                    <li>
                        <a href="#translations" data-toggle="tab"
                           aria-expanded="false"><?= \Yii::t('app', 'Translations') ?></a>
                    </li>
                </ul>

                <?php $form = UFActiveForm::begin([
                    'id' => 'trigger-update-form',
                    'options' => ['class' => 'js-trigger-form trigger-update-form'],
                    'fieldConfig' => [
                        'template' => '<div class="col-sm-2 col-xs-12 p-0">{label}</div><div class="col-sm-10 col-xs-12 p-0">{input}{error}{hint}</div>',
                    ]
                ]) ?>

                <div class="box-body">

                    <div class="tab-content js-edit-place-container">

                        <div class="tab-pane active" id="main">
                            <?= $this->render('/trigger/partial/trigger-form.php', $_params_ + ['form' => $form]) ?>
                        </div>
                        <div class="tab-pane" id="translations">
                            <?= $this->render('/trigger/partial/_translations', [
                                'model' => $trigger,
                                'languages' => Language::getList(),
                            ]) ?>
                        </div>

                        <div class="nav-tabs-buttons">

                            <?= Html::submitButton(
                                $trigger->isNewRecord
                                    ? \Yii::t('app', 'Create Trigger')
                                    : \Yii::t('app', 'Update Trigger'),
                                [
                                    'class' => 'js-button-process btn btn-success float-right',
                                    'value' => 'save',
                                ]
                            ) ?>

                        </div>
                    </div>
                </div>
                <?php UFActiveForm::end() ?>
                <?php if (!empty($receivers)): ?>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="js-receivers-form receivers-form box uf-form-create">

            <div class="box-header">
                <h3 class="box-title"><?= Yii::t('app', 'Receivers by AF') ?></h3>
            </div>

            <div class="box-body">
                <?= $this->render('/trigger/partial/receivers.php', $_params_) ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
