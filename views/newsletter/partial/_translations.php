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

    $content = $form->field($translation, '[' . $languageId . ']title')->label(\Yii::t('app', 'Title'))
        . $form->field($translation, '[' . $languageId . ']template')->label(\Yii::t('app', 'Template'))->textarea(['rows' => 4]);

    $items[] = [
        'header' => $header,
        'content' => $content,
    ];
}
?>
    <div class="box-body">
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
    </div>
<?php UFActiveForm::end() ?>