<?php
namespace app\src\service;

use app\src\entities\staff\Staff;
use app\src\entities\staff\StaffPositionPlace;
use app\src\library\Query;
use yii\helpers\ArrayHelper;

/**
 * Class StaffPositionPlaceService
 */
class StaffPositionPlaceService extends AbstractService
{
    public function getAvailableNewStaffIdsByClubId(int $clubId): array
    {
        $availableStaff = (new Query())
            ->from(['u' => Staff::tableName()])
            ->leftJoin(
                ['spp' => StaffPositionPlace::tableName()],
                'spp.user_id = u.id AND spp.place_id = :place_id',
                ['place_id' => $clubId]
            )
            ->andwhere([
                'u.status' => Staff::STATUS_ACTIVE,
                'spp.id' => null,
            ])
            ->select([
                'id' => 'u.id',
                'name' => 'u.username',
            ])
            ->all()
        ;

        return $availableStaff ? ArrayHelper::map($availableStaff, 'id', 'name') : [];
    }

    public function setPositionsByUserIdAndPlaceId(
        int $userId,
        int $placeId,
        array $positions = []
    ) {
        StaffPositionPlace::deleteAll([
            'user_id' => $userId,
            'place_id' => $placeId,
        ]);

        if (empty($positions)) {
            return;
        }

        $newSPPs = [];
        foreach ($positions as $positionId) {
            $spp = new StaffPositionPlace();
            $spp->user_id = $userId;
            $spp->place_id = $placeId;
            $spp->position_id = $positionId;

            $newSPPs[] = $spp;
        }

        StaffPositionPlace::batchInsertByModels($newSPPs);
    }
}