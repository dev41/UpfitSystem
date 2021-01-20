<?php
namespace app\src\service;

use app\src\entities\AbstractModel;
use app\src\entities\notification\Notification;
use app\src\entities\notification\WebNotification;
use app\src\entities\trigger\Trigger;

class WebNotificationService extends AbstractService implements INotificationService
{
    public function send(Trigger $trigger, $user, array $messages)
    {
        $notification = new WebNotification();

        if (!$trigger->event) {
            $notification->event = Trigger::EVENT_NEWSLETTER;
        } else {
            $notification->event = $trigger->event;
        }

        $notification->user_id = $user['id'];
        $notification->created_at = AbstractModel::getDateTimeNow();
        $notification->status = Notification::STATUS_CREATED;
        $notification->message = $messages['uk'];

        return $notification->save();
    }
}