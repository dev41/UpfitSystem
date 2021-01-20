<?php

use app\src\entities\translate\Language;
use yii\helpers\Html;
use yii\jui\Accordion;
use app\src\widget\UFActiveForm;

/* @var Language[] $languages */

$items = [];
$form = UFActiveForm::begin(['id' => 'club-update-info-form', 'options' => ['class' => 'js-club-update-info-form']]);
foreach ($languages as $languageId => $languageName) {
    $translation = $model->translations[$languageId];

    $header = Html::tag('div', '', ['class' => 'pull-left flag flag-' . $languageId]) . '&nbsp; ' . $languageName;

    $content = $form->field($translation, '[' . $languageId . ']address')->label(\Yii::t('app', 'Address'))
        . $form->field($translation, '[' . $languageId . ']description')->label(\Yii::t('app', 'Description'))->textarea(['rows' => 4]);

    $items[] = [
        'header' => $header,
        'content' => $content,
    ];
}
?>
    <div class="box-body">
        <div class="row">
            <div class="col-lg-8">
                <div class="translation-accordion">
                    <?= Accordion::widget([
                        'items' => $items,
                        'clientOptions' => [
                            'heightStyle' => 'content',
                            'animate' => ['duration' => 300],
                            'collapsible' => count($languages) > 1,
                            'active' => false,
                        ],
                        'headerOptions' => ['tag' => 'div'],
                    ]); ?>
                </div>

                <div class="box-footer">
                    <?= Html::submitButton(\Yii::t('app', 'Update Info'), [
                        'class' => 'js-button-process-info btn btn-success float-right',
                        'data-url' => 'update-info',
                        'data-form-name' => '.js-club-update-info-form',
                        'data-message' => [
                            'success' => Yii::t('app', 'Club data was saved.'),
                            'error' => Yii::t('app', 'Club data was\'t save!'),
                            'errorLoadImages' => Yii::t('app', 'Club images was\'t save!'),
                        ]
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
<?php UFActiveForm::end() ?>