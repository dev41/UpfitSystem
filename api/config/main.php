<?php
$rootDir = dirname(dirname(__DIR__));
$params = parse_ini_file($rootDir . '/config/params.ini', true, INI_SCANNER_TYPED);

$params = parse_ini_file($rootDir . '/config/params.ini', true, INI_SCANNER_TYPED);

$db = require $rootDir . '/config/db.php';
$liqpayConfig = require_once $rootDir . '/config/liqpay.php';

Yii::$classMap = Yii::$classMap + [
    'app\src\library\ApiApplication' => $rootDir . '/src/library/ApiApplication.php'
];

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'api\controllers',
    'homeUrl' => '/api',
    'modules' => [],
    'bootstrap' => ['log'],
    'aliases' => [
        '@api' => $rootDir . '/api',
        '@app' => $rootDir,
        '@siteUrl' => 'https://dev.upfit.com.ua/',
    ],
    'components' => [
        'db' => $db,
        'request' => [
            'baseUrl' => '/api',
            'enableCookieValidation' => false,
            'enableCsrfValidation' => false,
        ],
        'user' => [
            'identityClass' => 'app\src\entities\user\APIUser',
            'enableAutoLogin' => true,
            'enableSession' => false,
            'loginUrl' => ['site/default/login']
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'error/index',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [
                'v1/<controller:[\w-]+>/<action:[\w-]+>/' => '<controller>/<action>',
            ],
        ],
        'liqPay' => function() use ($liqpayConfig) {
            return new \app\src\library\LiqPay($liqpayConfig['public_key'], $liqpayConfig['private_key']);
        },
    ],

    'params' => array_merge($params, [
        'liqpayConfig' => $liqpayConfig
    ]),

    'timeZone' => 'Europe/London',
];
