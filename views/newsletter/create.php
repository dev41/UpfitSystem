<?php

use app\src\entities\trigger\Newsletter;
use app\src\entities\user\User;
use app\src\widget\UFActiveForm;
use yii\web\View;
use app\src\entities\translate\Language;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var View $this */
/* @var Newsletter $newsletter */
/* @var User[] $receivers */

$this->title = $newsletter->isNewRecord ? Yii::t('app', 'Newsletter Create') : Yii::t('app', 'Newsletter Update');

$this->params['breadcrumbs'][] = ['label' => \Yii::t('app', 'Newsletter'), 'url' => ['index']];
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
                    'id' => 'newsletter-update-form',
                    'options' => ['class' => 'js-newsletter-form newsletter-update-form'],
                    'fieldConfig' => [
                        'template' => '<div class="col-sm-2 col-xs-12 p-0">{label}</div><div class="col-sm-10 col-xs-12 p-0">{input}{error}{hint}</div>',
                    ]
                ]) ?>
                <div class="box-body">

                    <div class="tab-content js-edit-place-container">

                        <div class="tab-pane active" id="main">
                            <?= $this->render('/newsletter/partial/newsletter-form.php', $_params_ + ['form' => $form]) ?>
                        </div>
                        <div class="tab-pane" id="translations">
                            <?= $this->render('/newsletter/partial/_translations', [
                                'model' => $newsletter,
                                'languages' => Language::getList(),
                            ]) ?>
                        </div>

                        <div class="nav-tabs-buttons">

                            <?= Html::submitButton(
                                \Yii::t('app', 'Save'),
                                [
                                    'class' => 'js-button-process btn btn-success float-right',
                                    'value' => 'save',
                                ]
                            ) ?>

                            <?= Html::button(
                                Yii::t('app', 'Send'),
                                [
                                    'class' => 'js-button-send btn btn-success float-right mr-2',
                                    'data-url' => Url::to(['newsletter/send']),
                                    'data-model-id' => $newsletter->id ?? null,
                                    'data-notification-message' => [
                                        'success' => Yii::t('app', 'Newsletter have been successfully sent.'),
                                        'error' => Yii::t('app', 'Newsletter have\'t been sent.'),
                                    ],
                                    'value' => 'save',
                                ]
                            ) ?>

                        </div>
                    </div>
                </div>
                <?php UFActiveForm::end() ?>
            </div>
        </div>
    </div>
    <?php if (!empty($receivers)): ?>
        <div class="col-lg-4">
            <div class="js-receivers-form receivers-form box uf-form-create">

                <div class="box-header">
                    <h3 class="box-title"><?= Yii::t('app', 'Receivers') ?></h3>
                </div>

                <div class="box-body">
                    <?= $this->render('/newsletter/partial/receivers.php', $_params_) ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
