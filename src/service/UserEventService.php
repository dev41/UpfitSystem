<?php
namespace app\src\service;

use app\src\entities\coaching\Event;
use app\src\entities\user\User;
use app\src\entities\user\UserEvent;
use yii\helpers\ArrayHelper;

/**
 * Class UserEventService
 */
class UserEventService extends AbstractService
{
    public function setUserEventByEventId(int $eventId, $data = [])
    {
            UserEvent::deleteAll([
                'event_id' => $eventId,
            ]);

            if (empty($data)) {
                return;
            }

            $newUserEvent = [];
            foreach ($data as $userId) {
                $userEvent = new UserEvent();
                $userEvent->user_id = $userId;
                $userEvent->event_id = $eventId;

                $newUserEvent[] = $userEvent;
            }

            UserEvent::batchInsertByModels($newUserEvent);
    }

    public function deleteUserEventByDate(int $userId)
    {
        if (!$userId) {
            return;
        }

        $events = UserEvent::find()
            ->leftJoin(['e' => Event::tableName()], 'e.id = user_event.event_id')
            ->leftJoin(['u' => User::tableName()], 'u.id = user_event.user_id')
            ->where([
                'u.id' => $userId
            ])
            ->andWhere([
                '>', 'e.end', Event::getDateTimeNow()
            ])
            ->select([
                'event_id' => 'e.id',
            ])->asArray()->all();

        UserEvent::deleteAll(['event_id' => ArrayHelper::map($events, 'event_id', 'event_id'),
            'user_id' => $userId]);


    }

    public function deleteUserEvent(int $eventId, int $userId)
    {
        if (! $eventId || !$userId) {
            return;
        }

        UserEvent::deleteAll([
            'event_id' => $eventId,
            'user_id' => $userId,
        ]);

    }
}