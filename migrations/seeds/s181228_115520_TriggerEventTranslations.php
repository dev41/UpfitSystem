<?php
namespace app\migrations\seeds;

use app\src\BaseMigration;
use app\src\entities\translate\Message;
use app\src\entities\translate\Translation;
use Yii;use yii\helpers\ArrayHelper;

class s181228_115520_TriggerEventTranslations extends BaseMigration implements ISeeder
{
    private function getTranslations()
    {
        $pathToTranslations = \Yii::getAlias('@translations');
        return [
            require_once($pathToTranslations . '/uk/trigger_events.php'),
            require_once($pathToTranslations . '/ru/trigger_events.php'),
        ];
    }

    public function seed()
    {
        list($uaTranslation, $ruTranslation) = $this->getTranslations();

        foreach ($uaTranslation as $message => $translate) {

            $messageModel = new Message();
            $messageModel->category = 'app';
            $messageModel->message = $message;
            $messageModel->save();

            $translateUA = new Translation();
            $translateUA->id = $messageModel->id;
            $translateUA->translation = $translate;
            $translateUA->language = 'uk';
            $translateUA->save();

            $translateRU = new Translation();
            $translateRU->id = $messageModel->id;
            $translateRU->translation = $ruTranslation[$message];
            $translateRU->language = 'ru';
            $translateRU->save();
        }
    }

    public function clean()
    {
        list($uaTranslation, $ruTranslation) = $this->getTranslations();

        $translations = array_keys($uaTranslation);

        $messages = Message::find()->where(['message' => $translations])->all();
        $messageIds = ArrayHelper::map($messages, 'id', 'id');

        Translation::deleteAll(['id' => $messageIds]);
        Message::deleteAll(['id' => $messageIds]);
    }
}