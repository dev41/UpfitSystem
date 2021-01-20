<?php

namespace app\src\service;

use app\src\entities\trigger\Trigger;

class MobileNotificationService extends AbstractService implements INotificationService
{
    public function send(Trigger $trigger, $user, array $messages)
    {
        $config = \Yii::$app->params['oneSignalAPI'];
        if (empty($user['device_id'])) {
            return false;
        }

        $headings = [
            'en' => $trigger->title
        ];

        $templateId = null;
        if ($trigger->event) {
            $templateId = Trigger::ONESIGNAL_TEMPLATES[$trigger->event];
        }

        $fields = [
            'template_id' => $templateId,
            'app_id' => $config['app_id'],
            'contents' => $messages,
            'include_player_ids' => array($user['device_id']),
            'headings' => $headings,
        ];

        $fields = json_encode($fields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $config['base_url']);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
            'Authorization: Basic ' . $config['rest_api_key']));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $result = json_decode(curl_exec($ch));
        if ($result->errors) {
            return false;
        }
        curl_close($ch);

        return true;
    }
}