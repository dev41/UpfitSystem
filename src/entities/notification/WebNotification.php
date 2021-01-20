<?php
namespace app\src\entities\notification;

/**
 * Class WebNotification
 * @package app\src\entities\notification
 *
 * @property int $id
 * @property int $user_id
 * @property int $event
 * @property int $message
 * @property int $status
 * @property int $created_at
 */
class WebNotification extends Notification
{
    public static function tableName()
    {
        return 'web_notification';
    }

    public function rules()
    {
        return [
            [['event', 'user_id', 'status', 'message', 'created_at'], 'required'],
            [['id', 'event', 'user_id', 'status'], 'integer'],
            [['message'], 'string'],
        ];
    }
}