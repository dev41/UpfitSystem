<?php

namespace api\models;

use app\src\entities\coaching\Coaching;
use app\src\entities\coaching\CoachingI18n;
use app\src\entities\coaching\CoachingLevel;
use app\src\entities\coaching\CoachingPlace;
use app\src\entities\image\Image;
use app\src\entities\place\Place;
use app\src\entities\user\User;
use app\src\entities\user\UserCoaching;
use yii\db\Query;

class ApiCoaching extends Coaching
{
    public static function mapApiToDbFields()
    {
        return array_merge(self::$mapCoachingApiToDbFields, ApiClub::$mapClubApiToDbFields, ApiSubplace::$mapSubplaceApiToDbFields);
    }

    public static $mapCoachingApiToDbFields = [
        '#coaching_id' => 'c.id',
        '#coaching_parent_id' => 'c.parent_id',
        '#coaching_name' => 'c.name',
        '#coaching_description' => 'c.description',
        '#coaching_level' => 'cl.name',
        '#coaching_price' => 'c.price',
        '#coaching_capacity' => 'c.capacity',
        '#coaching_color' => 'c.color',
        '#coaching_created_at' => 'c.created_at',
        '#coaching_created_by' => 'c.created_by',
        '#coaching_updated_at' => 'c.updated_at',
        '#coaching_updated_by' => 'c.updated_by',

    ];

    public function getCoachingsQuery($language)
    {
        $query = (new Query())
            ->from(['c' => Coaching::tableName()])
            ->leftJoin(['cl' => CoachingLevel::tableName()], 'c.coaching_level_id = cl.id')
            ->leftJoin(['uc' => UserCoaching::tableName()], 'uc.coaching_id = c.id')
            ->leftJoin(['u' => User::tableName()], 'u.id = uc.user_id')
            ->leftJoin(['cp' => CoachingPlace::tableName()], 'cp.coaching_id = c.id')
            ->leftJoin(['club' => Place::tableName()], 'cp.place_id = club.id AND club.type = :place_type',
                ['place_type' => Place::TYPE_CLUB]
            )
            ->leftJoin(['place' => Place::tableName()], 'cp.place_id = place.id AND place.type !=' . Place::TYPE_CLUB)
            ->leftJoin(
                ['i' => Image::tableName()],
                'c.id = i.parent_id AND i.type =' . Image::TYPE_COACHING_IMAGE)
            ->andWhere(['or',
                ['u.status' => User::STATUS_ACTIVE],
                ['u.id' => NULL]
            ])
            ->select([
                'coaching_id' => 'c.id',
                'club_id' => 'GROUP_CONCAT(DISTINCT club.id)',
                'place_id' => 'GROUP_CONCAT(DISTINCT place.id)',
                'trainers' => 'GROUP_CONCAT(DISTINCT u.id SEPARATOR ", ")',
                'name' => 'c.name',
                'description' => 'c.description',
                'level' => 'cl.name',
                'price' => 'c.price',
                'image' => 'GROUP_CONCAT(DISTINCT i.file_name)',
                'color' => 'c.color',
                'capacity' => 'c.capacity',
            ])
            ->groupBy('c.id');

        if ($language){
            $query->leftJoin(['i18n' => CoachingI18n::tableName()], 'c.id = i18n.id')->where(['i18n.language' => $language])->addSelect([ 'name' => 'i18n.name', 'description' => 'i18n.description']);
        }

        return $query;
    }
}