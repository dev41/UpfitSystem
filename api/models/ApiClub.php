<?php
namespace api\models;

use api\formatter\ImagePathFormatter;
use app\src\entities\attribute\Attribute;
use app\src\entities\AbstractModel;
use app\src\entities\coaching\Coaching;
use app\src\entities\coaching\CoachingI18n;
use app\src\entities\coaching\CoachingPlace;
use app\src\entities\image\Image;
use app\src\entities\place\Club;
use app\src\entities\place\ClubI18n;
use app\src\entities\place\Place;
use app\src\entities\place\PlaceI18n;
use app\src\entities\place\Subplace;
use app\src\entities\staff\Staff;
use app\src\entities\staff\StaffPositionPlaceSearch;
use app\src\entities\user\Position;
use yii\db\Query;

class ApiClub extends Club
{
    public static function mapApiToDbFields()
    {
        return array_merge(self::$mapClubApiToDbFields, ApiCoaching::$mapCoachingApiToDbFields);
    }

    public static $mapClubApiToDbFields = [
        '#club_id' => 'p.id',
        '#club_name' => 'p.name',
        '#club_description' => 'p.description',
        '#club_address' => 'p.address',
        '#club_country' => 'p.country',
        '#club_city' => 'p.city',
        '#club_lat' => 'p.lat',
        '#club_lng' => 'p.lng',
        '#club_created_at' => 'p.created_at',
        '#club_created_by' => 'p.created_by',
        '#club_updated_at' => 'p.updated_at',
        '#club_updated_by' => 'p.updated_by',
    ];

    public function getClubsQuery($language)
    {
        $query = (new Query())
            ->from(['p' => Place::tableName()])
            ->where(['p.type' => Place::TYPE_CLUB])
            ->leftJoin(['l' => Image::tableName()], 'l.parent_id = p.id AND l.type =' . Image::TYPE_PLACE_LOGO)
            ->leftJoin(['cp' => CoachingPlace::tableName()], 'cp.place_id = p.id')
            ->leftJoin(['c' => Coaching::tableName()], 'cp.coaching_id = c.id')
            ->andWhere(['c.parent_id' => null])
            ->groupBy('p.id')
            ->select([
                'id' => 'p.id',
                'name' => 'p.name',
                'city' => 'p.city',
                'address' => 'p.address',
                'lat' => 'p.lat',
                'lng' => 'p.lng',
                'logo' => 'GROUP_CONCAT(l.file_name)',
                'coaching' => 'GROUP_CONCAT(DISTINCT c.id SEPARATOR ",")',
            ]);

        if ($language){
            $query->leftJoin(['i18n' => PlaceI18n::tableName()], 'p.id = i18n.id')->where(['i18n.language' => $language])->addSelect([ 'name' => 'i18n.name', 'address' => 'i18n.address']);
        }
        return $query;
    }

    public function getDetailsInfo(int $clubId, $language)
    {
        $clubQuery = (new Query())
            ->from(['p' => Place::tableName()])
            ->select([
                'id' => 'p.id',
                'name' => 'p.name',
                'type' => 'p.type',
                'phone_number' => 'p.phone_number',
                'email' => 'p.email',
                'site' => 'p.site',
                'facebook_id' => 'p.facebook_id',
                'instagram_id' => 'p.instagram_id',
                'city' => 'p.city',
                'address' => 'p.address',
                'images' => 'GROUP_CONCAT(DISTINCT i.file_name)',
                'logo' => 'GROUP_CONCAT(DISTINCT l.file_name)',
                'lat' => 'p.lat',
                'lng' => 'p.lng',
                'description' => 'p.description',
                'created_at' => 'DATE_FORMAT(p.created_at,\'' . AbstractModel::DATE_LISTING_FORMAT . '\')',
            ])
            ->where([
                'p.type' => Place::TYPE_CLUB,
                'p.id' => $clubId,
            ])
            ->leftJoin(['i' => Image::tableName()], 'i.parent_id = p.id AND i.type =' . Image::TYPE_PLACE_PHOTO)
            ->leftJoin(['l' => Image::tableName()], 'l.parent_id = p.id AND l.type =' . Image::TYPE_PLACE_LOGO)
            ->groupBy('p.id');

        if ($language) {
            $clubQuery
                ->leftJoin(['i18n' => PlaceI18n::tableName()], 'p.id = i18n.id')
                ->andWhere(['i18n.language' => $language])
                ->addSelect([
                    'name' => 'GROUP_CONCAT(DISTINCT i18n.name)',
                    'address' => 'GROUP_CONCAT(DISTINCT i18n.address)',
                    'description' => 'GROUP_CONCAT(DISTINCT i18n.description)',
                ]);
        }

        $clubData = ImagePathFormatter::format([$clubQuery->one()], Club::class, [
            'extPath' => '/logo',
        ]);

        $clubData = ImagePathFormatter::format($clubData, Club::class, [
            'fieldName' => 'images',
            'isDataTypeArray' => true
        ]);

        $staffPositionPlaceSearch = new StaffPositionPlaceSearch($clubId);
        /** @var Query $query */
        $query = $staffPositionPlaceSearch->getSearchDataProvider()->query;
        $query
            ->leftJoin(['i' => Image::tableName()], 'i.parent_id = spp.user_id AND i.type =' . Image::TYPE_USER_PHOTO)
            ->leftJoin(['a' => Image::tableName()], 'a.parent_id = spp.user_id AND a.type =' . Image::TYPE_USER_AVATAR)
            ->addSelect([
                'first_name' => 'u.first_name',
                'last_name' => 'u.last_name',
                'description' => 'u.description',
                'images' => 'GROUP_CONCAT(DISTINCT i.file_name SEPARATOR ", ")',
                'avatar' => 'GROUP_CONCAT(DISTINCT a.file_name)',
            ])
        ->andWhere([
            'pos.name' => Position::POSITION_TRAINER
        ]);
        $staffData = ImagePathFormatter::format($query->all(), Staff::class, [
            'fieldName' => 'avatar',
            'fieldId' => 'user_id',
            'extPath' => '/logo',
        ]);
        $staffData = ImagePathFormatter::format($staffData, Staff::class, [
            'fieldName' => 'images',
            'fieldId' => 'user_id',
            'isDataTypeArray' => true
        ]);

        $placeQuery = (new Query())
            ->from(['p' => Place::tableName()])
            ->select([
                'id' => 'p.id',
                'club_id' => 'p.parent_id',
                'name' => 'p.name',
                'type' => 'p.type',
                'city' => 'p.city',
                'address' => 'p.address',
                'images' => 'GROUP_CONCAT(DISTINCT i.file_name)',
                'description' => 'p.description',
                'created_at' => 'DATE_FORMAT(p.created_at,\'' . AbstractModel::DATE_LISTING_FORMAT . '\')',
            ])
            ->where(['!=', 'p.type', Place::TYPE_CLUB])
            ->andWhere(['p.parent_id' => $clubId])
            ->leftJoin(['i' => Image::tableName()], 'i.parent_id = p.id AND i.type =' . Image::TYPE_PLACE_PHOTO)
            ->groupBy('p.id');

        if ($language){
            $placeQuery->leftJoin(['i18n' => PlaceI18n::tableName()], 'p.id = i18n.id')->where(['i18n.language' => $language])->addSelect([ 'name' => 'i18n.name', 'address' => 'i18n.address', 'description' => 'i18n.description']);
        }

        $placeData = ImagePathFormatter::format($placeQuery->all(), Subplace::class, [
            'fieldName' => 'images',
            'isDataTypeArray' => true
        ]);

        $coachingQuery = Coaching::find()->where(['parent_id' => null]);

        if ($language){
            $coachingQuery->leftJoin(['i18n' => CoachingI18n::tableName()], 'coaching.id = i18n.id')->where(['i18n.language' => $language])->addSelect([ 'name' => 'i18n.name', 'description' => 'i18n.description']);
        }

        return [
            'club' => $clubData,
            'trainer' => $staffData,
            'places' => $placeData,
            'coaching' => $coachingQuery->all(),
            'attributes' => Attribute::findAll(['parent_id' => $clubId])
        ];
    }
}