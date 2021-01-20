<?php
namespace app\controllers;

use app\src\dataProviders\CoachingFormDataProvider;
use app\src\entities\coaching\CoachingSearch;
use app\src\entities\coaching\CopyCoaching;
use app\src\entities\coaching\Event;
use app\src\entities\place\Club;
use app\src\entities\place\Subplace;
use app\src\entities\user\User;
use app\src\library\AccessChecker;
use app\src\library\BaseController;
use app\src\service\EventService;
use DateInterval;

class ScheduleController extends BaseController
{

    public function actionIndex()
    {
        $clubId = $this->getParam('club_id');
        $events = Event::getFCEvents($clubId);

        $coachingSearch = new CoachingSearch();
        $coachingDataProvider = $coachingSearch->getSearchDataProvider([
            'clubId' => $clubId,
        ]);

        $coachingDataProvider->pagination = false;

        $clubs = AccessChecker::isSuperAdmin() ?
            Club::getAll() :
            Club::getClubsByUserId(\Yii::$app->user->getId());

        $this->appendEntryPoint();

        return $this->render('index', [
            'place' => new Subplace(),
            'events' => $events,
            'clubId' => $clubId,
            'coaching' => $coachingDataProvider->getModels(),
            'clubs' => $clubs,
        ]);
    }

    public function actionGetScheduleAsComponent()
    {
        $clubId = $this->getParam('clubId');
        $coachingId = $this->getParam('coachingId');

        $coachingSearch = new CoachingSearch();

        if ($clubId) {
            $events = Event::getFCEvents($clubId);
            $coachingDataProvider = $coachingSearch->getSearchDataProvider([
                'clubId' => $clubId,
            ]);
        } else {
            $events = Event::getFCEventsByCoachingId($coachingId);
            $coachingDataProvider = $coachingSearch->getSearchDataProvider([
                'coachingId' => $coachingId,
            ]);
        }

        $clubs = AccessChecker::isSuperAdmin() ?
            Club::getAll() :
            Club::getClubsByUserId(\Yii::$app->user->getId());

        $component = $this->renderAjax('index', [
            'place' => new Subplace(),
            'events' => $events,
            'coachingId' => $coachingId,
            'renderAjax' => true,
            'coaching' => $coachingDataProvider->getModels(),
            'clubId' => $clubId,
            'clubs' => $clubs,
        ]);

        return $this->responseJson([
            'html' => $component,
        ]);
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function actionGetNewEventForm()
    {
        $coachingId = (int) $this->getParam('coachingId');
        $start = $this->getParam('start');
        $clubId = $this->getParam('clubId');

        $coaching = CopyCoaching::findOne(['id' => $coachingId]);
        $event = new Event();
        $event->start = $start;

        $endTime = new \DateTime($start);
        $endTime->add(new DateInterval('PT60M'));
        $event->end = $endTime->format('Y-m-d H:i:s');

        $coachingData = new CoachingFormDataProvider($coaching, [
            'event' => $event,
            'customers' => User::getCustomers($clubId),
            'clubId' => $clubId,
        ]);
        $eventForm = $this->renderAjax('create-event-by-coaching', $coachingData->getData());

        return $this->responseJson([
            'form' => $eventForm,
        ]);
    }

    public function actionGetUpdateEventForm()
    {
        $eventId = (int) $this->getParam('eventId');
        $clubId = $this->getParam('clubId');
        $event = Event::findOne($eventId);

        $coachingData = new CoachingFormDataProvider($event->coaching, [
            'event' => $event,
            'customers' => User::getCustomers($clubId),
            'clubId' => $clubId,
        ]);
        $eventForm = $this->renderAjax('create-event-by-coaching', $coachingData->getData());

        return $this->responseJson([
            'form' => $eventForm,
        ]);
    }

    public function actionNewEvent()
    {
        $attributes = $this->getParams();

        $coachingCopy = new CopyCoaching();
        $coachingCopy->load($attributes);
        $coachingId = (int) $coachingCopy->id;

        $eventService = new EventService();
        $event = $eventService->createEventByCoachingIdAndData($coachingId, $attributes);

        return $this->responseJson([
            'event' => Event::getFCEventById($event->id),
            'message' => [
                'success' => \Yii::t('app', 'Event have been successfully created.')
            ]
        ]);
    }

    public function actionCopyEvent()
    {
        $eventId = (int) $this->getParam('eventId');
        $start = $this->getParam('start');
        $end = $this->getParam('end');

        if (!$end) {
            $endTime = new \DateTime($start);
            $endTime->add(new DateInterval('PT120M'));

            $end = $endTime->format('Y-m-d H:i:s');
        }

        $event = Event::findOne($eventId);

        $eventService = new EventService();
        $event = $eventService->copyEvent($event, $start, $end);

        return $this->responseJson([
            'event' => Event::getFCEventById($event->id),
        ]);
    }

    public function actionUpdateEvent()
    {
        $eventId = (int) $this->getParam('eventId');
        $attributes = $this->getParams();

        $event = Event::findOne($eventId);

        $eventService = new EventService();
        $event = $eventService->updateEventByData($event, $attributes);

        return $this->responseJson([
            'event' => Event::getFCEventById($event->id),
            'message' => [
                'success' => \Yii::t('app', 'Event have been successfully updated.')
            ]
        ]);
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function actionUpdateEventDate()
    {
        $eventId = (int) $this->getParam('eventId');
        $start = $this->getParam('start');
        $end = $this->getParam('end');

        if (!$end) {
            $endTime = new \DateTime($start);
            $endTime->add(new DateInterval('PT120M'));

            $end = $endTime->format('Y-m-d H:i:s');
        }

        $eventService = new EventService();
        $eventService->changeEventDate($eventId, $start, $end);

        return $this->responseJson([
            'message' => [
                'success' => \Yii::t('app', 'Event date have been successfully changed.')
            ]
        ]);
    }

    public function actionDeleteEvent()
    {
        $eventId = (int) $this->getParam('eventId');
        $event = Event::findOne($eventId);

        $eventService = new EventService();
        $eventService->deleteEvent($event);

        return $this->responseJson([
            'message' => [
                'success' => \Yii::t('app', 'Event have been successfully deleted.')
            ]
        ]);
    }

}