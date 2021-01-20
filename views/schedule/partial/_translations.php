<?php

use app\src\entities\translate\Language;
use yii\helpers\Html;
use yii\jui\Accordion;

/* @var Language[] $languages */

$items = [];

foreach ($languages as $languageId => $languageName) {
    $translation = $model->translations[$languageId];

    $header = Html::tag('div', '', ['class' => 'pull-left flag flag-' . $languageId]) . '&nbsp; ' . $languageName;

    $content = $form->field($translation, '[' . $languageId . ']name')->label(\Yii::t('app', 'Name'))
        . $form->field($translation, '[' . $languageId . ']description')->label(\Yii::t('app', 'Description'))->textarea(['rows' => 4]);

    $items[] = [
        'header' => $header,
        'content' => $content,
    ];
}
?>

<div class="translation-accordion">
    <?= Accordion::widget([
        'items' => $items,
        'id' => 'translations-coaching',
        'clientOptions' => [
            'heightStyle' => 'content',
            'animate' => ['duration' => 300],
            'collapsible' => count($languages) > 1,
            'active' => false,
        ],
        'headerOptions' => ['tag' => 'div'],
    ]); ?>
</div>

<br />