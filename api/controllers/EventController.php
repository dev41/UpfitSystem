<?php
namespace api\controllers;

use api\formatter\IdToEntityFormatter;
use api\formatter\ImagePathFormatter;
use api\formatter\StringToArrayFormatter;
use api\models\ApiEvent;
use app\src\entities\coaching\Coaching;
use app\src\entities\image\Image;
use app\src\entities\staff\Staff;
use app\src\entities\user\User;
use app\src\exception\ApiException;
use app\src\library\BaseApiController;
use app\src\library\Query;
use app\src\service\EventService;
use app\src\service\UserEventService;

class EventController extends BaseApiController
{
    /**
     * @return array
     * @throws ApiException
     */
    public function actionIndex()
    {
        $clubId = $this->getParam('club_id');

        $apiEvent = new ApiEvent();

        /** @var Query $query */
        $query = $apiEvent->getEventsQuery($clubId);

        $searchBuilder = $this->getSearchBuilder($query, ApiEvent::mapApiToDbFields());
        $responseData = $searchBuilder->getModels();

        $responseData = IdToEntityFormatter::format($responseData, User::class, 'trainers',
            function(\yii\db\Query $query) {
                return $query
                    ->leftJoin(['i' => Image::tableName()], 'i.parent_id = user.id AND i.type =' . Image::TYPE_USER_PHOTO)
                    ->leftJoin(['a' => Image::tableName()], 'a.parent_id = user.id AND a.type =' . Image::TYPE_USER_AVATAR)
                    ->addSelect([
                        'images' => 'GROUP_CONCAT(DISTINCT i.file_name SEPARATOR ", ")',
                        'avatar' => 'GROUP_CONCAT(DISTINCT a.file_name)',
                    ]);
            }
        );
        $responseData = StringToArrayFormatter::format($responseData, 'customersId');

        $responseData = array_map(function ($row) {
            if (!empty($row['trainers'])) {
                $row['trainers'] = ImagePathFormatter::format($row['trainers'], Staff::class, [
                    'extPath' => ImagePathFormatter::LOGO_SUB_DIR,
                    'fieldName' => 'avatar',
                ]);
                $row['trainers'] = ImagePathFormatter::format($row['trainers'], Staff::class, [
                    'fieldName' => 'images',
                    'isDataTypeArray' => true,
                ]);
            }

            return $row;
        }, $responseData);

        $responseData = ImagePathFormatter::format($responseData, Coaching::class, [
            'fieldId' => 'coaching_id',
            'fieldName' => 'image'
        ]);

        return $this->response([
            'event' => $responseData,
        ]);
    }

    /**
     * @return array
     * @throws ApiException
     * @throws \app\src\exception\ModelValidateException
     */
    public function actionAddCustomer()
    {
        $eventId = (int) $this->getParam('event_id');

        if (!$eventId) {
            throw new ApiException(ApiException::REQUIRED_PARAMS_NOT_FOUND, ['param' => 'event_id']);
        }

        $customerId = (int) $this->getParam('user_id');

        if (!$customerId) {
            $customerId = \Yii::$app->user->getId();
        }

        $eventService = new EventService();
        $userEventOrPaymentUrl = $eventService->addCustomer($eventId, $customerId);

        return $this->response($userEventOrPaymentUrl);
    }

    /**
     * @return array
     * @throws ApiException
     */
    public function actionLeaveEvent()
    {
        $eventId = (int)$this->getParam('event_id');

        if (!$eventId) {
            throw new ApiException(ApiException::REQUIRED_PARAMS_NOT_FOUND, ['param' => 'event_id']);
        }

        $userEventService = new UserEventService();
        $userEventService->deleteUserEvent($eventId, \Yii::$app->user->getId());

        return $this->response();
    }
}