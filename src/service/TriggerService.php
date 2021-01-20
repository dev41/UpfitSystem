<?php
namespace app\src\service;

use app\src\entities\AbstractModel;
use app\src\entities\trigger\Trigger;
use app\src\entities\trigger\TriggerPlace;
use app\src\entities\trigger\TriggerPosition;
use app\src\entities\trigger\TriggerRole;
use app\src\entities\trigger\TriggerType;
use app\src\entities\trigger\TriggerUser;

class TriggerService extends AbstractService
{

    public function createByData(array $data, string $scope = null, int $userId = null): Trigger
    {
        $transaction = \Yii::$app->db->beginTransaction();

        try {

            $trigger = new Trigger();
            $trigger->load($data, $scope);

            $trigger->created_by = $trigger->created_by ?? $userId ?? \Yii::$app->user->id;
            $trigger->created_at = $trigger->created_at ?? AbstractModel::getDateTimeNow();

            $trigger->save();

            $transaction->commit();

        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }

        return $trigger;
    }

    public function updateByData(int $id, array $data, string $scope = null, int $userId = null): Trigger
    {
        $transaction = \Yii::$app->db->beginTransaction();

        try {

            $trigger = Trigger::findOne($id);
            $trigger->load($data, $scope);

            $trigger->updated_by = $trigger->updated_by ?? $userId ?? \Yii::$app->user->id;
            $trigger->updated_at = $trigger->updated_at ?? AbstractModel::getDateTimeNow();

            $trigger->save();

            $transaction->commit();

        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }

        return $trigger;
    }

    public function setPlaces(int $triggerId, array $placeIds)
    {
        TriggerPlace::deleteAll([
            'trigger_id' => $triggerId,
            'type' => TriggerPlace::TYPE_FILTER_CLUB
        ]);

        if (empty($placeIds)) {
            return;
        }

        $triggerPlaces = [];

        foreach ($placeIds as $placeId) {

            $triggerPlace = new TriggerPlace();
            $triggerPlace->trigger_id = $triggerId;
            $triggerPlace->place_id = (int) $placeId;
            $triggerPlace->type = TriggerPlace::TYPE_FILTER_CLUB;

            $triggerPlaces[] = $triggerPlace;
        }

        TriggerPlace::batchInsertByModels($triggerPlaces);
    }

    public function setPositions(int $triggerId, array $positionIds)
    {
        TriggerPosition::deleteAll([
            'trigger_id' => $triggerId,
        ]);

        if (empty($positionIds)) {
            return;
        }

        $triggerPositions = [];

        foreach ($positionIds as $positionId) {

            $triggerPosition = new TriggerPosition();
            $triggerPosition->trigger_id = $triggerId;
            $triggerPosition->position_id = (int) $positionId;

            $triggerPositions[] = $triggerPosition;
        }

        TriggerPosition::batchInsertByModels($triggerPositions);
    }

    public function setRoles(int $triggerId, array $roleIds)
    {
        TriggerRole::deleteAll([
            'trigger_id' => $triggerId,
        ]);

        if (empty($roleIds)) {
            return;
        }

        $triggerRoles = [];

        foreach ($roleIds as $roleId) {

            $triggerRole = new TriggerRole();
            $triggerRole->trigger_id = $triggerId;
            $triggerRole->role_id = (int) $roleId;

            $triggerRoles[] = $triggerRole;
        }

        TriggerRole::batchInsertByModels($triggerRoles);
    }

    public function setUsers(int $triggerId, array $userIds)
    {
        TriggerUser::deleteAll([
            'trigger_id' => $triggerId,
        ]);

        if (empty($userIds)) {
            return;
        }

        $triggerUsers = [];

        foreach ($userIds as $userId) {

            $triggerUser = new TriggerUser();
            $triggerUser->trigger_id = $triggerId;
            $triggerUser->user_id = (int) $userId;

            $triggerUsers[] = $triggerUser;
        }

        TriggerUser::batchInsertByModels($triggerUsers);
    }

    public function setTypes(int $triggerId, string $types)
    {
        TriggerType::deleteAll([
            'trigger_id' => $triggerId,
        ]);

        $typesIds = array_filter(explode(',', $types));

        if (empty($typesIds)) {
            return;
        }

        $triggerTypes = [];

        foreach ($typesIds as $key => $typeId) {

            $triggerType = new TriggerType();
            $triggerType->trigger_id = $triggerId;
            $triggerType->type = (int) $typeId;
            $triggerType->priority = (int) $key;

            $triggerTypes[] = $triggerType;
        }

        TriggerUser::batchInsertByModels($triggerTypes);
    }

    public function setClubs(int $triggerId, array $clubsIds)
    {
        TriggerPlace::deleteAll([
            'trigger_id' => $triggerId,
            'type' => TriggerPlace::TYPE_PARENT_CLUB
        ]);

        if (empty($clubsIds)) {
            return;
        }

        $triggerPlaces = [];

        foreach ($clubsIds as $clubId) {

            $triggerPlace = new TriggerPlace();
            $triggerPlace->trigger_id = $triggerId;
            $triggerPlace->type = TriggerPlace::TYPE_PARENT_CLUB;
            $triggerPlace->place_id = (int) $clubId;

            $triggerPlaces[] = $triggerPlace;
        }

        TriggerUser::batchInsertByModels($triggerPlaces);
    }
}