<?php

namespace app\src\widget;

use kartik\daterange\DateRangePicker;

class UFDateRangePicker extends DateRangePicker
{
    public static function widget($config = [])
    {
        return parent::widget(array_merge_recursive([
            'presetDropdown' => false,
            'hideInput' => true,
            'pluginEvents' => [
                "cancel.daterangepicker" => "function(ev, picker) {
                                picker.element[0].children[1].textContent = '';
                                $(picker.element[0].nextElementSibling).val('').trigger('change');
                                }",
            ],
            'convertFormat' => true,
            'pluginOptions' => [
                'timePickerIncrement' => 30,
                'locale' => [
                    'format' => 'Y-m-d',
                ],
                'ranges' => [
                    \Yii::t('app', 'All time') => ["moment('2000-01-01 00:00:00')", "moment().endOf('day')"],
                    \Yii::t('app', 'Today') => ["moment().startOf('day')", "moment().endOf('day')"],
                    \Yii::t('app', 'Yesterday') => ["moment().startOf('day').subtract(1,'days')", "moment().endOf('day').subtract(1,'days')"],
                    \Yii::t('app', 'Last 7 Days') => ["moment().startOf('day').subtract(6, 'days')", "moment().endOf('day')"],
                    \Yii::t('app', 'Last 30 Days') => ["moment().startOf('day').subtract(29, 'days')", "moment().endOf('day')"],
                    \Yii::t('app', 'This Month') => ["moment().startOf('month')", "moment().endOf('month')"],
                    \Yii::t('app', 'Last Month') => ["moment().subtract(1, 'month').startOf('month')", "moment().subtract(1, 'month').endOf('month')"]
                ],
            ],
        ], $config));
    }
}