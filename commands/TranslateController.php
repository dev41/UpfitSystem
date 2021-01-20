<?php
namespace app\commands;

use app\src\entities\translate\Message;
use yii\console\Controller;

class TranslateController extends Controller
{
    public function actionCheckNewMessageAfterParsing()
    {
        $pathToTranslations = \Yii::getAlias('@translations');
        $uaTranslation = require_once($pathToTranslations . '/uk/base_translations.php');
        $ruTranslation = require_once($pathToTranslations . '/ru/base_translations.php');
        $fileName = $pathToTranslations . '/new_message.php';

        $messages = Message::find()->all();

        $uaMessages = '';
        $ruMessages = '';
        /**
         * @var Message $message
         */
        foreach ($messages as $message) {
            if (!isset($uaTranslation[$message->message])) {
                $uaMessages .= $message->message . "\n";
            }
            if (!isset($ruTranslation[$message->message])) {
                $ruMessages .= $message->message . "\n";
            }
        }

        file_put_contents($fileName, 'UA:' . "\n" . $uaMessages, FILE_APPEND);
        file_put_contents($fileName, "\n" . 'RU:' . "\n" . $ruMessages, FILE_APPEND);
    }
}