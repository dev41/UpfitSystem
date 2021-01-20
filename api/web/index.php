<?php
$message = str_repeat('-', 50) . "\n\n";
$message .= 'server time: ' . date('Y-m-d H:i:s') . "\n";
$message .= "SERVER:\n";
$message .= print_r($_SERVER, true);
$message .= "POST:\n";
$message .= print_r($_POST, true);
$message .= "GET:\n";
$message .= print_r($_POST, true);
$message .= "REQUEST:\n";
$message .= print_r($_REQUEST, true);
$message .= "\n\n";
$fileName = __DIR__ . '/../../api_log.txt';

if (filesize($fileName) > 200*1024) {
    unlink($fileName);
}

file_put_contents($fileName, $message, FILE_APPEND);
//
//die('REQUEST_METHOD: ' . $_SERVER['REQUEST_METHOD']);

//$ch = curl_init();
//
//curl_setopt($ch, CURLOPT_URL,"http://app.upfit.com.ua/api/v1/user/login-fb");
//curl_setopt($ch, CURLOPT_POST, 1);
//curl_setopt($ch, CURLOPT_POSTFIELDS,
//    "id=181724282660547&token=EAAB5Wx1RbKQBALwh3MEMzoVqBb6iFm23aY3iVZC7SfY3WVJvmfjQtvCqkc5Q9WfS5QUaDnU6sZCT4ZCXWG5xzd5DLh8dZB3cPZBRWZCLHqgt9EwzK38gEnCbX07xGhXDZBBp4hR1i8Om9jry8GrWiqC1lYZBaL5wsJRZCt1RNNKtZA6P7KxBTWZAM0rQ5zjMr0iWPtrspKa82yk9gZDZD");
//
//curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//
//$server_output = curl_exec ($ch);
//
//curl_close ($ch);

defined('YII_DEBUG') or define('YII_DEBUG', false);
defined('YII_ENV') or define('YII_ENV', 'dev');

define('YII_ENABLE_ERROR_HANDLER', true);
define('YII_ENABLE_EXCEPTION_HANDLER', true);

require(__DIR__ . '/../../vendor/autoload.php');
require(__DIR__ . '/../../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../config/main.php');

$application = new \app\src\library\ApiApplication($config);
$application->run();