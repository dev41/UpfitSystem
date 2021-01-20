<?php
namespace app\src\service;

use app\src\entities\coaching\CoachingPlace;

class CoachingPlaceService extends AbstractService
{
    public function copyPlacesByCoachingId(int $coachingIdFrom, int $coachingIdTo)
    {
        $transaction = \Yii::$app->db->beginTransaction();

        try {
            $places = CoachingPlace::findAll([
                'coaching_id' => $coachingIdFrom,
            ]);

            if (!$places) {
                return;
            }

            $copyPlaces = [];
            foreach ($places as $place) {
                $copyPlace = new CoachingPlace();
                $copyPlace->setAttributes($place->getAttributes());
                $copyPlace->coaching_id = $coachingIdTo;

                $copyPlaces[] = $copyPlace;
            }

            CoachingPlace::batchInsertByModels($copyPlaces);

            $transaction->commit();
        } catch (\Exception $e) {

            $transaction->rollBack();
            throw $e;
        }
    }
}