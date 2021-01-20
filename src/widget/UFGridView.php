<?php

namespace app\src\widget;

use yii\grid\GridView;

class UFGridView extends GridView
{
    public static function widget($config = [])
    {
        return parent::widget(array_merge_recursive([
            'summary' => '',

            'tableOptions' => [
                'class' => 'table table-bordered table-hover'
            ],

            'pager' => [
                'options' => [
                    'tag' => 'ul',
                    'class' => 'pagination list-pagination float-right',
                ],
                'prevPageLabel' => '<div style="border: none">' . \Yii::t('app', 'Previous') . '</div>',
                'nextPageLabel' => '<div style="border: none">' . \Yii::t('app', 'Next') . '</div>',
            ],
        ], $config));
    }
}