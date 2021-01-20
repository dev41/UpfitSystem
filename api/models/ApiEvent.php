<?php
namespace api\models;

use app\src\entities\coaching\Coaching;
use app\src\entities\coaching\CoachingI18n;
use app\src\entities\coaching\CoachingLevel;
use app\src\entities\coaching\CoachingPlace;
use app\src\entities\coaching\Event;
use app\src\entities\image\Image;
use app\src\entities\place\Place;
use app\src\entities\user\User;
use app\src\entities\user\UserCoaching;
use app\src\entities\user\UserEvent;
use yii\db\Query;

class ApiEvent extends Event
{
    public static function mapApiToDbFields()
    {
        return array_merge(self::$mapEventApiToDbFields, ApiClub::$mapClubApiToDbFields, ApiSubplace::$mapSubplaceApiToDbFields);
    }

    public static $mapEventApiToDbFields = [
        '#event_id' => 'e.id',
        '#event_name' => 'c.name',
        '#event_description' => 'c.description',
        '#event_level' => 'cl.name',
        '#event_price' => 'c.price',
        '#event_capacity' => 'c.capacity',
        '#event_start' => 'e.start',
        '#event_end' => 'e.end',
        '#event_created_at' => 'e.created_at',
        '#event_updated_at' => 'e.updated_at',
        '#event_updated_by' => 'u.id',
        '#event_created_by' => 'u.id',
    ];

    public function getEventsQuery($clubId)
    {
        $query = (new Query())
            ->from(['e' => $this::tableName()])
            ->leftJoin(['c' => Coaching::tableName()], 'e.coaching_id = c.id')
            ->leftJoin(['cl' => CoachingLevel::tableName()], 'c.coaching_level_id = cl.id')
            ->leftJoin(['pc' => Coaching::tableName()], 'pc.id = c.parent_id')
            ->leftJoin(['pcl' => CoachingLevel::tableName()], 'pc.coaching_level_id = pcl.id')

            ->leftJoin(['uc' => UserCoaching::tableName()], 'uc.coaching_id = c.id')
            ->leftJoin(['u' => User::tableName()], 'u.id = uc.user_id')

            ->leftJoin(['puc' => UserCoaching::tableName()], 'uc.coaching_id = pc.id')
            ->leftJoin(['pu' => User::tableName()], 'u.id = puc.user_id')

            ->leftJoin(['ue' => UserEvent::tableName()], 'ue.event_id = e.id')
            ->leftJoin(['customer' => User::tableName()], 'ue.user_id = customer.id')
            ->leftJoin(['cp' => CoachingPlace::tableName()], 'cp.coaching_id = c.id')
            ->leftJoin(['place' => Place::tableName()], 'cp.place_id = place.id AND place.type !=' . Place::TYPE_CLUB)
            ->leftJoin(['club' => Place::tableName()], 'club.id = place.parent_id AND club.type = :place_type',
                ['place_type' => Place::TYPE_CLUB]
            )
            ->leftJoin(
                ['i' => Image::tableName()],
                'c.parent_id = i.parent_id AND i.type =' . Image::TYPE_COACHING_IMAGE)
            ->andWhere(['or',
                ['u.status' => User::STATUS_ACTIVE],
                ['u.id' => NULL]
            ])
            ->andWhere(['or',
                ['customer.status' => User::STATUS_ACTIVE],
                ['customer.id' => NULL]
            ])
            ->select([
                'event_id' => 'e.id',
                'coaching_id' => 'c.parent_id',
                'club_id' => 'GROUP_CONCAT(DISTINCT club.id)',
                'place_id' => 'GROUP_CONCAT(DISTINCT place.id)',
                'trainers' => 'COALESCE(
                    GROUP_CONCAT(DISTINCT u.id SEPARATOR ","),
                    GROUP_CONCAT(DISTINCT pu.id SEPARATOR ",")
                )',
                'start' => 'e.start',
                'end' => 'e.end',
                'title' => 'COALESCE(c.name, pc.name)',
                'description' => 'COALESCE(c.description, pc.description)',
                'level' => 'COALESCE(cl.name, pcl.name)',
                'price' => 'COALESCE(c.price, pc.price)',
                'image' => 'GROUP_CONCAT(DISTINCT i.file_name)',
                'color' => 'COALESCE(c.color, pc.color)',
                'capacity' => 'COALESCE(c.capacity, pc.capacity)',
                'customersId' => 'GROUP_CONCAT(DISTINCT customer.id SEPARATOR ", ")',
            ])->groupBy('e.id');

        if ($clubId) {
            $query->andWhere(['club.id' => $clubId]);
        }

        return $query;
    }
}