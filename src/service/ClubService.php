<?php
namespace app\src\service;

use app\src\entities\place\Club;
use app\src\entities\place\Place;
use app\src\entities\staff\StaffPositionPlace;
use app\src\entities\user\Position;

class ClubService extends AbstractService
{
    public function createEmptyClub(array $attributes, int $ownerId = null): Club
    {
        $transaction = \Yii::$app->db->beginTransaction();
        $ownerId = $ownerId ?? \Yii::$app->user->id;

        try {
            $club = new Club();
            $club->type = Place::TYPE_CLUB;
            $club->created_by = \Yii::$app->user->id;
            $club->created_at = Place::getDateTimeNow();
            $club->load($attributes);
            $club->save();

            $spp = new StaffPositionPlace();
            $spp->validateOnlyDBFields = true;
            $spp->user_id = $ownerId;
            $spp->place_id = $club->id;
            $spp->position_id = Position::getIdByName(Position::POSITION_OWNER);
            $spp->save();

            $transaction->commit();
        } catch (\Exception $e) {

            $transaction->rollBack();
            throw $e;
        }

        return $club;
    }
}