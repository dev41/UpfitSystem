<?php
namespace api\models;

use app\src\entities\AbstractModel;
use app\src\entities\activity\Activity;
use app\src\entities\activity\ActivityI18n;
use app\src\entities\customer\CustomerPlace;
use app\src\entities\image\Image;
use app\src\entities\place\Place;
use app\src\entities\user\UserActivity;
use yii\db\Query;

class ApiActivity extends Activity
{
    public static function mapApiToDbFields()
    {
        return array_merge(ApiClub::$mapClubApiToDbFields, self::$mapActivityApiToDbFields);
    }

    public static $mapActivityApiToDbFields = [
        '#activity_id' => 'a.id',
        '#activity_name' => 'a.name',
        '#activity_description' => 'a.description',
        '#activity_price' => 'a.price',
        '#activity_capacity' => 'a.capacity',
        '#activity_start' => 'a.start',
        '#activity_end' => 'a.end',
        '#activity_created_at' => 'a.created_at',
        '#activity_created_by' => 'a.created_by',
    ];

    public function getActivitiesQuery($userId, $language)
    {
        $query = (new Query())
            ->from(['a' => $this::tableName()])
            ->where(['or',
                ['cp.user_id' => $userId],
                ['c.created_by' => $userId],
                ['a.created_by' => $userId],
            ])
            ->leftJoin(['c' => Place::tableName()], 'a.club_id = c.id AND c.type =' . Place::TYPE_CLUB)
            ->leftJoin(['cp' => CustomerPlace::tableName()], 'cp.place_id = c.id')
            ->leftJoin(
                ['i' => Image::tableName()],
                'a.id = i.parent_id AND i.type =' . Image::TYPE_ACTIVITY_IMAGE)
            ->leftJoin(['ua' => UserActivity::tableName()], 'ua.activity_id = a.id AND ua.is_staff =' . $this::IS_STAFF)
            ->groupBy('a.id')
            ->select([
                'id' => 'a.id',
                'club_id' => 'a.club_id',
                'name' => 'a.name',
                'description' => 'a.description',
                'price' => 'a.price',
                'capacity' => 'a.capacity',
                'start' => 'a.start',
                'end' => 'a.end',
                'created_at' => 'DATE_FORMAT(a.created_at,\'' . AbstractModel::DATE_LISTING_FORMAT . '\')',
                'image' => 'GROUP_CONCAT(DISTINCT i.file_name)',
                'organizers' => 'GROUP_CONCAT(DISTINCT ua.user_id SEPARATOR ",")',
            ]);

        if ($language){
            $query->leftJoin(['i18n' => ActivityI18n::tableName()], 'a.id = i18n.id')->where(['i18n.language' => $language])->addSelect([ 'name' => 'i18n.name', 'description' => 'i18n.description']);
        }

        return $query;
    }
}