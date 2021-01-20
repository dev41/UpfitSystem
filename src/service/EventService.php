<?php
namespace app\src\service;

use app\src\entities\AbstractModel;
use app\src\entities\coaching\Coaching;
use app\src\entities\coaching\CoachingPlace;
use app\src\entities\coaching\Event;
use app\src\entities\customer\CustomerPlace;
use app\src\entities\place\Club;
use app\src\entities\place\Place;
use app\src\entities\purchase\Purchase;
use app\src\entities\trigger\Trigger;
use app\src\entities\user\UserEvent;
use app\src\exception\ApiException;
use app\src\library\EventManager;
use yii\db\Query;
use yii\helpers\ArrayHelper;

class EventService extends AbstractService
{
    /**
     * @param int $eventId
     * @param int $customerId
     * @return UserEvent|array|null
     * @throws ApiException
     * @throws \app\src\exception\ModelValidateException
     */
    public function addCustomer(int $eventId, int $customerId)
    {
        $event = Event::findOne($eventId);

        if (!$event) {
            throw new ApiException(ApiException::MODEL_NOT_FOUND, ['model' => 'event']);
        }

        $eventCoaching = (new Query())
            ->from(['c' => Coaching::tableName()])
            ->leftJoin(['pc' => Coaching::tableName()], 'pc.id = c.parent_id')
            ->select([
                'price' => 'COALESCE(c.price, pc.price)',
                'capacity' => 'COALESCE(c.capacity, pc.capacity)',
            ])->andWhere(['c.id' => $event->coaching_id])->one();

        $coachingPlace = CoachingPlace::findOne([
            'coaching_id' => $event->coaching_id,
            'place_type' => Place::TYPE_CLUB
        ]);

        $customerPlace = CustomerPlace::findOne([
            'user_id' => $customerId,
            'place_id' => $coachingPlace->place_id,
            'status' => CustomerPlace::STATUS_CONFIRMED
        ]);

        if (!$customerPlace) {
            throw new ApiException(ApiException::CUSTOMER_NOT_IN_CLUB);
        }

        $users = $event->getUsers();

        if (count($users) >= $eventCoaching['capacity']) {
            throw new ApiException(ApiException::EVENT_CAPACITY_LIMIT, ['capacity' => $eventCoaching['capacity']]);
        }

        $userEvent = UserEvent::findOne([
            'user_id' => $customerId,
            'event_id' => $eventId,
        ]);

        if ($userEvent) {
            throw new ApiException(ApiException::USER_EVENT_SUBSCRIBE_EXIST);
        }

        $userEvent = new UserEvent();

        if ($eventCoaching['price'] > 0) {
            $purchaseService = new PurchaseService();
            $paymentUrl = $purchaseService->getPaymentUrl($coachingPlace->place_id, $customerId, [
                'amount' => $eventCoaching['price'],
                'expired_date' => date('Y-m-d H:i:s', strtotime('+ 10 minutes')),
                'product_type' => Purchase::PRODUCT_TYPE_TRAINING,
                'product_id' => $event->id
            ]);

            $userEvent->status = UserEvent::STATUS_PENDING_FOR_PAYMENT;
        } else {
            $userEvent->status = UserEvent::STATUS_CONFIRMED;
        }

        $userEvent->user_id = $customerId;
        $userEvent->event_id = $eventId;
        $userEvent->save();

        return $paymentUrl ?? ['userEvent' => $userEvent];
    }

    public function createEventByCoachingIdAndData(int $coachingId, array $data = [], int $userId = null): Event
    {
        $coachingService = new CoachingService();
        $userId = $userId ?? \Yii::$app->user->id;
        $transaction = \Yii::$app->db->beginTransaction();
        $eventService = new UserEventService();

        try {
            $coaching = $coachingService->createCopy($coachingId, $data);

            $event = new Event();
            $event->load($data);
            $event->coaching_id = $coaching->id;
            $event->created_by = $event->created_by ?? $userId;
            $event->created_at = $event->created_at ?? AbstractModel::getDateTimeNow();

            $event->save();
            $eventService->setUserEventByEventId($event->id,$event->users);

            $transaction->commit();
        } catch (\Exception $e) {

            $transaction->rollBack();
            throw $e;
        }

        return $event;
    }

    /**
     * @param Event $event
     * @param array $data
     * @param int|null $userId
     * @return Event
     * @throws \Exception
     */
    public function updateEventByData(Event $event, array $data = [], int $userId = null): Event
    {
        $userId = $userId ?? \Yii::$app->user->id;
        $transaction = \Yii::$app->db->beginTransaction();
        $coachingService = new CoachingService();
        $eventService= new UserEventService();

        try {
            $event->load($data);
            $event->updated_by = $event->updated_by ?? $userId;
            $event->updated_at = $event->updated_at ?? AbstractModel::getDateTimeNow();
            $event->save();

            $coachingService->updateByData($event->coaching, $data);
            $eventService->setUserEventByEventId($event->id,$event->users);

            $transaction->commit();
        } catch (\Exception $e) {

            $transaction->rollBack();
            throw $e;
        }

        return $event;
    }

    /**
     * @param int $eventId
     * @param $start
     * @param $end
     * @param int|null $userId
     * @throws \app\src\exception\ModelValidateException
     * @throws \Exception
     */
    public function changeEventDate(int $eventId, $start, $end, int $userId = null)
    {
        if (!$eventId || !$start || !$end) {
            return;
        }

        $event = Event::findOne($eventId);

        $oldStart = $event->start;
        $oldEnd = $event->end;

        $event->start = $start;
        $event->end = $end;
        $event->updated_at = Event::getDateTimeNow();
        $event->updated_by = $userId ?? $this->getLoggedUserId();

        $coaching = Coaching::findOne(['id' => $event->coaching_id]);
        $coachingPlace = CoachingPlace::findOne(['coaching_id' => $coaching->id, 'place_type' => Place::TYPE_CLUB]);

        EventManager::trigger(Trigger::EVENT_CHANGE_EVENT_DATE, function () use ($event, $oldStart, $oldEnd, $coaching) {
            $codes = [];

            $codes['coaching.name'] = $coaching->name;

            $clubs = Place::find()
                ->leftJoin(['cp' => CoachingPlace::tableName()], 'cp.place_id = place.id')
                ->where(['cp.coaching_id' => $coaching->id])
                ->andWhere(['place.type' => Place::TYPE_CLUB])
                ->all();

            foreach ($clubs as $club) {
                $codes['coaching.clubs'][] = $club['name'];
            }

            $eventData = $event->toArray();
            foreach ($eventData as $key => $value) {
                $codes['event.' . $key] = $value;
            }

            $codes['event.old_start'] = $oldStart;
            $codes['event.old_end'] = $oldEnd;

            return $codes;
        }, $event->users, $coachingPlace->place_id);

        $event->save();
    }

    public function copyEvent(Event $copyEvent, string $start, string $end, int $userId = null): Event
    {
        $newEventData = [
            Event::getShortClassName() => [
                'start' => $start,
                'end' => $end,
            ],
        ];

        return $this->createEventByCoachingIdAndData($copyEvent->coaching_id, $newEventData, $userId);
    }

    public function deleteEvent(Event $event)
    {
        $coaching = Coaching::findOne(['id' => $event->coaching_id]);
        $event = Event::findOne(['coaching_id' => $coaching->id]);

        $coachingClubs = CoachingPlace::findAll(['coaching_id' => $coaching->id, 'place_type' => Place::TYPE_CLUB]);
        $users = $event->getUsers();
        $coachingClubsIds = ArrayHelper::map($coachingClubs, 'place_id', 'place_id');
        $clubs = Club::findAll(['id' => $coachingClubsIds]);

        EventManager::trigger(Trigger::EVENT_CANCELED, function () use ($coaching, $clubs) {
            $codes = [];

            $codes['coaching.name'] = $coaching->name;

            foreach ($clubs as $club) {
                $codes['coaching.clubs'][] = $club['name'];
            }

            return $codes;
        }, $users, $coachingClubsIds);

        $event->delete();
    }
}