<?php
use kartik\datecontrol\Module;

// hidden field always set '' /var/www/upfitsystem/vendor/kartik-v/yii2-datecontrol/assets/js/datecontrol.js:58

return [
    'datecontrol' =>  [
        'class' => '\kartik\datecontrol\Module',

        'ajaxConversion' => false,

        'displaySettings' => [
            Module::FORMAT_DATE => 'php:M d, Y',
            Module::FORMAT_TIME => 'HH:ii:s',
            Module::FORMAT_DATETIME => 'dd-MM-yyyy HH:i',
        ],

        // format settings for saving each date attribute (PHP format example)
        'saveSettings' => [
            Module::FORMAT_DATE => 'php:Y-m-d',
            Module::FORMAT_TIME => 'php:H:i:s',
            Module::FORMAT_DATETIME => 'php:Y-m-d H:i:s',
        ],

        'autoWidgetSettings' => [
            Module::FORMAT_DATE => [
                'pluginOptions' => [
                    'autoclose' => true,
                    'todayBtn' => true,
                    'todayHighlight' => true,
                    'dateSettings' => [
                        'longDays' => ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
                        'shortDays' => ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
                        'shortMonths' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                        'longMonths' => ['January', 'February', 'March', 'April', 'May', 'June',
                            'July', 'August', 'September', 'October', 'November', 'December'],
                        'meridiem' => ['AM', 'PM'],
                    ],
                ],
            ],
            Module::FORMAT_DATETIME => [
                'pluginOptions' => [
                    'autoclose' => true,
                    'todayBtn' => true,
                    'todayHighlight' => true,
                ],
            ],
            Module::FORMAT_TIME => [
                'pluginOptions' => [
                    'autoclose' => true,
                ],
            ],
        ],
    ],
];