<?php

$params = parse_ini_file(__DIR__ . '/params.ini', true, INI_SCANNER_TYPED);

$db = require_once __DIR__ . '/db.php';
$liqpayConfig = require_once __DIR__ . '/liqpay.php';
$kartikModules = require_once __DIR__ . '/kartik_modules.php';

$config = [

    'id' => 'app-backend',
    'name' => 'UpFit System',

    'basePath' => dirname(__DIR__),

    'bootstrap' => [
        'log',
        'eventManager',
        'liqPay',
    ],

    'language' => 'en',

    'modules' => [
        'permit' => [
            'class' => 'developeruz\db_rbac\Yii2DbRbac',
        ],
        'gridview' =>  [
            'class' => '\kartik\grid\Module',
        ],
    ],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@src' => '@app/src',
        '@widget' => '@src/widget',
        '@npm' => '@vendor/npm-asset',
        '@web' => '@app/web',
        '@imageStorage' => '@web/storage/images',
    ],

    'components' => [

        'assetManager' => [
            'class' => 'yii\web\AssetManager',
            'forceCopy' => true,
            'bundles' => [
                'yii\jui\JuiAsset' => [
                'css' => [
                    'web/css/main.css',
                    ]
                ]
            ]
        ],

        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],

        'user' => [
            'identityClass' => 'app\src\entities\user\User',
            'enableAutoLogin' => true,
        ],

        'view' => [
            'theme' => [
                'pathMap' => [
                    '@app/views' => 'app/views'
                ],
            ],
        ],

        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.gmail.com',
                'username' => 'AndreyPolishchuk.drv@gmail.com',
                'password' => 'cjxiablqjwkstvjs',
                'port' => '587',
                'encryption' => 'tls',
            ],
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

        'db' => $db,

        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'yYy4YYYX8lYyYyQOl8vOcO6ROo7i8twO',
//            'cookieValidationKey' => 'tsQ8UoGB98eLqNsiePMkym3Q8LuYB8HD',
            'baseUrl' => '',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],

        'errorHandler' => [
            'errorAction' => 'error/index',
        ],

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'baseUrl'=> '',
                '' => 'site/index',
                '<controller:[\w-]+>/<action:[\w-]+>/' => '<controller>/<action>',
                ['class' => 'yii\rest\UrlRule', 'controller' => 'user'],
            ],
        ],
        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\DbMessageSource',
                    'sourceMessageTable' => '{{%i18n_message}}',
                    'messageTable' => '{{%i18n_translation}}',
                    //'class' => 'yii\i18n\PhpMessageSource',
                    //'basePath' => '@common/messages',
                    //'sourceLanguage' => 'en-US',
                    /*'fileMap' => [
                        'app'       => 'app.php',
                        'app/error' => 'error.php',
                    ],*/
                ],
            ],
        ],

        'eventManager' => \app\src\library\EventManager::class,
        'liqPay' => function() use ($liqpayConfig) {
            return new \app\src\library\LiqPay($liqpayConfig['public_key'], $liqpayConfig['private_key']);
        },
    ],

    'container' => [
        'definitions' => [
            \app\src\widget\UFActiveForm::class => [
                'fieldConfig' => [
                    'template' => '<div class="col-sm-3 col-xs-12">{label}</div><div class="col-sm-9 col-xs-12">{input}{error}{hint}</div>',
                    'options' => [
                        'class' => 'row'
                    ],
                ],
            ],
        ],
    ],

    'controllerMap' => [
        'api_v1_user' => '\app\apiControllers\UserController',
    ],

    'params' => array_merge($params, [
        'reset_password_token_expire_period' => 60*60*24,
        'system_email' => 'upfitsystem@gmail.com',
        'liqpayConfig' => $liqpayConfig
    ]),
];

$config['modules'] = array_merge($config['modules'], $kartikModules);

if (YII_ENV_DEV) {
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => ['*'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['*'],
    ];
}

return $config;

