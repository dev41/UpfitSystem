<?php
namespace app\src\service;

use app\src\entities\AbstractModel;
use app\src\entities\coaching\Coaching;
use app\src\entities\coaching\CoachingPlace;
use app\src\entities\image\Image;
use app\src\entities\place\Club;
use app\src\entities\place\Place;
use app\src\entities\place\PlaceI18n;
use app\src\entities\place\Subplace;
use app\src\entities\place\SubplaceI18n;
use app\src\exception\ModelValidateException;
use yii\helpers\ArrayHelper;

/**
 * Class PlaceService
 */
class PlaceService extends AbstractService
{
    public function getModelByType(int $type): Place
    {
        switch ($type) {
            case Place::TYPE_CLUB: return new Club();
            case Place::TYPE_SUB_PLACE:
            case Place::TYPE_GYM:
            case Place::TYPE_OPEN_SPACE: return new Subplace();
        }

        return new Place();
    }

    /**
     * @param array $data
     * @param int $type
     * @param string|null $scope
     * @param int|null $userId
     * @return Place
     * @throws ModelValidateException
     */
    public function createPlaceByData(array $data, $type = Place::TYPE_CLUB, string $scope = null, int $userId = null): Place
    {
        $place = $this->getModelByType($type);
        $place->load($data, $scope);

        $place->type = $place->type ?? $type;
        $place->created_by = $place->created_by ?? $userId ?? \Yii::$app->user->id;
        $place->created_at = $place->created_at ?? AbstractModel::getDateTimeNow();

        $place->save();

        return $place;
    }

    public function copySubplaceById(int $id): Place
    {
        $place = Subplace::findOne($id);

        if (!$place) {
            throw new ModelValidateException(new Subplace());
        }

        $attributes = $place->getAttributes();

        $copyAttributes = array_intersect_key($attributes, array_flip(Subplace::COPY_FIELDS));

        $copyPlace = new Place();
        $copyPlace->setAttributes($copyAttributes);
        $copyPlace->created_at = AbstractModel::getDateTimeNow();
        $copyPlace->created_by = \Yii::$app->user->getId();

        $copyPlace->save(false);

        //translate
        $placeI18ns = SubplaceI18n::findAll($id);

        if ($placeI18ns){

            foreach ($placeI18ns as $placeI18n) {

                $attributes = $placeI18n->getAttributes();

                $copyAttributes = array_intersect_key($attributes, array_flip(SubplaceI18n::COPY_FIELDS));

                $copyPlaceI18n = new PlaceI18n();
                $copyPlaceI18n->setAttributes($copyAttributes);
                $copyPlaceI18n->id = $copyPlace->id;
                $copyPlaceI18n->save(false);
            }
        }
        return $copyPlace;
    }

    public function updatePlaceByData(int $id, array $data, $type = Place::TYPE_CLUB): Place
    {
        $place = $this->getModelByType($type);

        $place = $place::findOne($id);
        $place->load($data);

        $imageService = new ImageService();
        $imageService->uploadImages($place, $_FILES, Image::TYPE_PLACE_PHOTO);
        $imageService->uploadLogo($place, $_FILES, Image::TYPE_PLACE_LOGO);

        $place->updated_at = $place->updated_at ?? AbstractModel::getDateTimeNow();
        $place->updated_by = $place->updated_by ?? \Yii::$app->user->getId();

        $place->save();

        return $place;
    }

    /**
     * @param array $data
     * @return Place
     * @throws \Exception
     */
    public function createClubByData(array $data): Place
    {
        $transaction = \Yii::$app->db->beginTransaction();

        try {
            /** @var Club $club */
            $club = $this->createPlaceByData($data, Place::TYPE_CLUB);

            $transaction->commit();

        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }

        return $club;
    }

    /**
     * @param Club $club
     * @param array $data
     * @return Place
     * @throws \Exception
     */
    public function updateClubByData(Club $club, array $data): Place
    {
        $transaction = \Yii::$app->db->beginTransaction();

        try {
            /** @var Club $club */
            $club = $this->updatePlaceByData($club->id, $data, Place::TYPE_CLUB);

            $transaction->commit();

        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }

        return $club;
    }

    /**
     * @param int $placeId
     *
     * relation tables:
     *
     * coaching
     * event
     * user_event
     *
     * place
     * coaching_place
     *
     * user_position_place
     *
     * @throws \Exception
     * @throws \yii\db\Exception
     */
    public function deleteById(int $placeId)
    {
        $transaction = \Yii::$app->db->beginTransaction();

        try {
            $coachingPlaces = CoachingPlace::findAll([
                'place_id' => $placeId,
            ]);

            if ($coachingPlaces) {
                $coachingIds = ArrayHelper::map($coachingPlaces, 'coaching_id', 'coaching_id');

                // delete data from tables (by CASCADE foreign key): coaching, event, user_event
                Coaching::deleteAll([
                    'id' => $coachingIds,
                ]);
            }

            // delete sub places and relations from user_position_place
            Place::deleteAll([
                'parent_id' => $placeId,
            ]);

            // delete place
            Place::deleteAll([
                'id' => $placeId,
            ]);

            $transaction->commit();
        } catch (\Exception $e) {

            $transaction->rollBack();
            throw $e;
        }
    }

}