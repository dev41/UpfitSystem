<?php
namespace app\migrations\seeds;

use app\src\BaseMigration;
use app\src\entities\translate\Message;
use app\src\entities\translate\Translation;
use Yii;

class s181213_101306_Translation extends BaseMigration implements ISeeder
{
    public function seed()
    {
        $this->consoleLog('parsing translations...');

        exec("php " . Yii::$app->basePath . "/yii message " . Yii::$app->basePath . "/config/i18n.php");

        $pathToTranslations = \Yii::getAlias('@translations');
        $uaTranslation = require_once($pathToTranslations . '/uk/base_translations.php');
        $ruTranslation = require_once($pathToTranslations . '/ru/base_translations.php');

        $messages = Message::find()->all();

        $this->consoleLog('create translations...');

        /**
         * @var Message $message
         */
        foreach ($messages as $message) {
            if (isset($uaTranslation[$message->message])) {
                $this->update(Translation::tableName(),
                    ['translation' => $uaTranslation[$message->message]],
                    [
                        'id' => $message->id,
                        'language' => 'uk',
                    ]
                );
            }
            if (isset($ruTranslation[$message->message])) {
                $this->update(Translation::tableName(),
                    ['translation' => $ruTranslation[$message->message]],
                    [
                        'id' => $message->id,
                        'language' => 'ru',
                    ]
                );
            }
        }
    }

    public function clean()
    {
        $this->update(Translation::tableName(),
            ['translation' => null]
        );
    }
}