<?php
namespace app\src\service;

use app\src\entities\AbstractModel;
use app\src\entities\coaching\Coaching;
use app\src\entities\coaching\CoachingPlace;
use app\src\entities\coaching\CopyCoaching;
use app\src\entities\coaching\Event;
use app\src\entities\image\Image;
use app\src\entities\place\Club;
use app\src\entities\place\Place;
use app\src\entities\trigger\Trigger;
use app\src\entities\user\User;
use app\src\entities\user\UserCoaching;
use app\src\exception\ModelValidateException;
use app\src\library\EventManager;
use yii\helpers\ArrayHelper;

class CoachingService extends AbstractService
{
    public function createByData($data, int $userId = null): Coaching
    {
        $transaction = \Yii::$app->db->beginTransaction();
        $userId = $userId ?? \Yii::$app->user->id;

        try {
            $coaching = new Coaching();
            $coaching->load($data);

//            $this->checkArePaidTrainingAvailable($coaching);

            $coaching->created_by = $coaching->created_by ?? $userId;
            $coaching->created_at = $coaching->created_at ?? AbstractModel::getDateTimeNow();

            $coaching->save();

            $imageService = new ImageService();
            $imageService->uploadImages($coaching, $_FILES, Image::TYPE_COACHING_IMAGE);

            $transaction->commit();
        } catch (\Exception $e) {

            $transaction->rollBack();
            throw $e;
        }

        return $coaching;
    }

    public function updateByData(Coaching $coaching, $data, int $userId = null): Coaching
    {
        $transaction = \Yii::$app->db->beginTransaction();
        $userId = $userId ?? \Yii::$app->user->id;

        try {
            $coaching->load($data);

//            $this->checkArePaidTrainingAvailable($coaching);

            $coaching->updated_by = $coaching->updated_by ?? $userId;
            $coaching->updated_at = $coaching->updated_at ?? AbstractModel::getDateTimeNow();

            $coaching->save();

            $imageService = new ImageService();
            $imageService->uploadImages($coaching, $_FILES, Image::TYPE_COACHING_IMAGE, true);

            $transaction->commit();
        } catch (\Exception $e) {

            $transaction->rollBack();
            throw $e;
        }

        return $coaching;
    }

    public function setTrainers(int $coachingId, array $trainerIds = [])
    {
        $isChanged = false;
        $usersCoaching = UserCoaching::findAll(['coaching_id' => $coachingId]);
        $coachingUserIds = ArrayHelper::map($usersCoaching, 'user_id', 'user_id');

        if ($trainerIds && !$coachingUserIds || !$trainerIds && $coachingUserIds) {
            $isChanged = true;
        } else {
            foreach ($coachingUserIds as $coachingUserId) {
                foreach ($trainerIds as $trainerId) {
                    if ($coachingUserId != (int)$trainerId) {
                        $isChanged = true;
                    }
                }
            }
        }

        UserCoaching::deleteAll([
            'coaching_id' => $coachingId,
        ]);

        $coaching = Coaching::findOne(['id' => $coachingId]);
        $event = Event::findOne(['coaching_id' => $coachingId]);
        if ($coaching->parent_id && $event && $isChanged) {
            $coachingClubs = CoachingPlace::findAll(['coaching_id' => $coachingId, 'place_type' => Place::TYPE_CLUB]);
            $users = $event->getUsers();
            $coachingClubsIds = ArrayHelper::map($coachingClubs, 'place_id', 'place_id');
            $clubs = Club::findAll(['id' => $coachingClubsIds]);

            EventManager::trigger(Trigger::EVENT_CHANGE_TRAINER, function () use ($coaching, $clubs) {
                $codes = [];

                $codes['coaching.name'] = $coaching->name;

                foreach ($clubs as $club) {
                    $codes['coaching.clubs'][] = $club['name'];
                }

                return $codes;
            }, $users, $coachingClubsIds);
        }

        if (empty($trainerIds)) {
            return;
        }

        $userTrainers = [];
        foreach ($trainerIds as $trainerId) {
            $userCoaching = new UserCoaching();
            $userCoaching->coaching_id = $coachingId;
            $userCoaching->user_id = $trainerId instanceof User ? $trainerId->id : $trainerId;

            $userTrainers[] = $userCoaching;
        }

        UserCoaching::batchInsertByModels($userTrainers);
    }

    public function setPlaces(int $coachingId, int $placeType, array $placeIds = [])
    {
        CoachingPlace::deleteAll([
            'coaching_id' => $coachingId,
            'place_type' => $placeType,
        ]);

        if (empty($placeIds)) {
            return;
        }

        $coachingPlaces = [];
        foreach ($placeIds as $placeId) {
            $coachingPlace = new CoachingPlace();
            $coachingPlace->coaching_id = $coachingId;
            $coachingPlace->place_type = $placeType;
            $coachingPlace->place_id = $placeId;

            $coachingPlaces[] = $coachingPlace;
        }

        CoachingPlace::batchInsertByModels($coachingPlaces);
    }

    public function createCopy(int $coachingId, array $data = []): Coaching
    {
        $transaction = \Yii::$app->db->beginTransaction();

        try {
            $copyFrom = CopyCoaching::findOne($coachingId);
            $coachingData = $copyFrom->getAttributes();

            $baseCoachingData = CopyCoaching::exportData($data);
            $basedOnFormData = isset($baseCoachingData['id']);

            // create coaching based on form data
            if ($basedOnFormData) {
                $coachingData['parent_id'] = $baseCoachingData['id'];
                $coachingData = array_merge($coachingData, $baseCoachingData);
            // create coaching based on parent coaching
            } else {
                $coachingData['parent_id'] = $copyFrom->parent_id;
                $parentCoaching = CopyCoaching::findOne($copyFrom->parent_id);
                $coachingData = array_merge($parentCoaching->toArray(), $coachingData);
            }

            unset($coachingData['id']);

            $newCoaching = new CopyCoaching();
            $newCoaching->setAttributes($coachingData);
            $newCoaching->isCopy = true;

            $newCoaching->save(false);

            if (!$newCoaching->trainers && !$basedOnFormData) {
                $userCoachingService = new UserCoachingService();
                $userCoachingService->copyUsers($copyFrom->id, $newCoaching->id);
            }

            if (!$newCoaching->clubs && !$newCoaching->places && !$basedOnFormData) {
                $coachingPlaceService = new CoachingPlaceService();
                $coachingPlaceService->copyPlacesByCoachingId(
                    $copyFrom->id,
                    $newCoaching->id
                );
            }

            $transaction->commit();
        } catch (\Exception $e) {

            $transaction->rollBack();
            throw $e;
        }

        return $newCoaching;
    }

    /**
     * @param Coaching|CopyCoaching $coaching
     * @param null $clubsIds
     * @return bool
     * @throws \Exception
     */
    public function checkArePaidTrainingAvailable($coaching, $clubsIds = null)
    {
        if ($coaching->price > 0) {
            $clubsIds = $clubsIds ?? $coaching->clubs;
            $coachingClubs = Club::findAll(['id' => $clubsIds]);

            $clubsWithoutFilledPaymentFields = '';
            foreach ($coachingClubs as $club) {
                if (!($club->public_key) || !($club->private_key)) {
                    $clubsWithoutFilledPaymentFields .= $club->name . ', ';
                };
            }
            if ($clubsWithoutFilledPaymentFields) {
                throw new ModelValidateException($coaching, [
                    'price' => [\Yii::t('app', 'The club ') . $clubsWithoutFilledPaymentFields . \Yii::t('app', ' did not enter payment fields')]
                ]);
            }
        }
        return true;
    }

}