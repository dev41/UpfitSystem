<?php
namespace app\src\service;

use app\src\entities\access\AccessRole;
use app\src\entities\customer\Customer;
use app\src\entities\customer\CustomerPlace;
use app\src\entities\place\Place;
use app\src\entities\trigger\Trigger;
use app\src\entities\user\User;
use app\src\library\EventManager;
use yii\db\Query;
use yii\helpers\ArrayHelper;

class CustomerPlaceService extends AbstractService
{
    public function addCustomersToClub($clubId, array $customerIds, int $status = CustomerPlace::STATUS_CONFIRMED)
    {
        $insertModels = [];
        foreach ($customerIds as $userId) {
            $customerPlace = new CustomerPlace();
            $customerPlace->place_id = $clubId;
            $customerPlace->user_id = $userId;
            $customerPlace->status = $status;
            $insertModels[] = $customerPlace;
        }

        CustomerPlace::batchInsertByModels($insertModels, ['created_at']);
    }

    /**
     * @param int $clubId
     * @param int $customerId
     * @param int [$status]
     * @param string [$cardNumber]
     * @return CustomerPlace
     * @throws \app\src\exception\ModelValidateException
     */
    public function addCustomerToClub(
        int $clubId,
        int $customerId,
        int $status = CustomerPlace::STATUS_CONFIRMED,
        string $cardNumber = null
    ) {
        $customerPlace = new CustomerPlace();
        $customerPlace->validateOnlyDBFields = true;
        $customerPlace->place_id = $clubId;
        $customerPlace->user_id = $customerId;
        $customerPlace->status = $status;
        $customerPlace->card_number = $cardNumber;
        $customerPlace->save();

        return $customerPlace;
    }

    /**
     * @param int $userId
     * @param int $clubId
     * @param null $cardNumber
     * @throws \app\src\exception\ModelValidateException
     */
    public function requestCustomerToClub(int $userId, int $clubId, $cardNumber = null)
    {
        if (!$userId || !$clubId) {
            return;
        }

        $user = User::findOne($userId);

        EventManager::trigger(Trigger::EVENT_REQUEST_CUSTOMER_TO_CLUB, function () use ($clubId, $user) {
            $codes = [];

            $club = Place::findOne($clubId)->toArray();
            foreach ($club as $key => $value) {
                $codes['club.' . $key] = $value;
            }

            $user = $user->toArray();
            foreach ($user as $key => $value) {
                $codes['user.' . $key] = $value;
            }

            return $codes;
        }, [$user], $clubId);

        $this->addCustomerToClub($clubId, $userId, CustomerPlace::STATUS_PENDING, $cardNumber);
    }

    public function rejectCustomerFromClub(int $userId, int $clubId)
    {
        if (!$userId || !$clubId) {
            return;
        }

        $user = User::findOne($userId);

        EventManager::trigger(Trigger::EVENT_CUSTOMER_REJECTED_FROM_CLUB, function () use ($clubId, $user) {
            $codes = [];

            $club = Place::findOne($clubId)->toArray();
            foreach ($club as $key => $value) {
                $codes['club.' . $key] = $value;
            }

            $user = $user->toArray();
            foreach ($user as $key => $value) {
                $codes['user.' . $key] = $value;
            }

            return $codes;
        }, [$user], $clubId);

        CustomerPlace::deleteAll([
            'place_id' => $clubId,
            'user_id' => $userId,
        ]);
    }

    /**
     * @param int $userId
     * @param int $placeId
     * @throws \app\src\exception\ModelValidateException
     */
    public function toggleStatusByUserIdAndPlaceId(int $userId, int $placeId)
    {
        if (!$userId || !$placeId) {
            return;
        }

        $customerPlace = CustomerPlace::findOne(['user_id' => $userId, 'place_id' => $placeId]);

        $event = ($customerPlace->status === CustomerPlace::STATUS_PENDING) ?
            Trigger::EVENT_CUSTOMER_ACCEPTED_TO_CLUB : Trigger::EVENT_CUSTOMER_REJECTED_FROM_CLUB;

        $user = User::findOne(['id' => $userId]);

        EventManager::trigger($event, function () use ($placeId, $user) {
            $codes = [];

            $club = Place::findOne($placeId)->toArray();
            foreach ($club as $key => $value) {
                $codes['club.' . $key] = $value;
            }

            $user = $user->toArray();
            foreach ($user as $key => $value) {
                $codes['user.' . $key] = $value;
            }

            return $codes;
        }, [$user], $placeId);

        $customerPlace->status = !$customerPlace->status;

        $userCurrentRole = AccessRole::findOne($user->role_id);

        if ($userCurrentRole->slug === AccessRole::ROLE_APP_USER && $customerPlace->status) {
            $clientRole = AccessRole::getRoleBySlug(AccessRole::ROLE_CLIENT);
            $user->role_id = $clientRole->id;
            $user->save(false);
        }

        $customerPlace->save(false);
    }

    public function getAvailableNewCustomerIdsForClub(int $clubId): array
    {
        $availableCustomers = (new Query())
            ->from(['u' => Customer::tableName()])
            ->leftJoin(
                ['cp' => CustomerPlace::tableName()],
                'cp.user_id = u.id AND cp.place_id = :place_id',
                ['place_id' => $clubId]
            )
            ->andwhere([
                'u.status' => Customer::STATUS_ACTIVE,
                'cp.user_id' => null,
            ])
            ->select([
                'id' => 'u.id',
                'name' => 'u.username',
            ])
            ->all()
        ;

        return $availableCustomers ? ArrayHelper::map($availableCustomers, 'id', 'name') : [];
    }
}