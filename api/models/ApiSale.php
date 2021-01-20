<?php
namespace api\models;

use app\src\entities\customer\CustomerPlace;
use app\src\entities\image\Image;
use app\src\entities\place\Place;
use app\src\entities\sale\Sale;
use yii\db\Query;

class ApiSale extends Sale
{
    public static function mapApiToDbFields()
    {
        return array_merge(self::$mapSaleApiToDbFields, ApiClub::$mapClubApiToDbFields);
    }

    public static $mapSaleApiToDbFields = [
        '#sale_id' => 's.id',
        '#club_id' => 's.club_id',
        '#sale_name' => 's.name',
        '#sale_description' => 's.description',
        '#sale_start' => 's.start',
        '#sale_end' => 's.end',
        '#sale_created_at' => 's.created_at',
        '#sale_created_by' => 's.created_by',
    ];

    public function getSaleQuery($userId, $language)
    {
        $query = (new Query())
            ->from(['s' => Sale::tableName()])
            ->where(['or',
                ['cp.user_id' => $userId],
                ['c.created_by' => $userId],
                ['s.created_by' => $userId],
            ])
            ->leftJoin(['c' => Place::tableName()], 's.club_id = c.id AND c.type =' . Place::TYPE_CLUB)
            ->leftJoin(['cp' => CustomerPlace::tableName()], 'cp.place_id = c.id')
            ->leftJoin(
                ['i' => Image::tableName()],
                's.id = i.parent_id AND i.type =' . Image::TYPE_SALE_IMAGE)
            ->groupBy('s.id')
            ->select([
                'id' => 's.id',
                'club_id' => 's.club_id',
                'name' => 's.name',
                'description' => 's.description',
                'start' => 's.start',
                'end' => 's.end',
                'created_at' => 's.created_at',
                'image' => 'GROUP_CONCAT(DISTINCT i.file_name)',
            ]);

        if ($language){
            $query->leftJoin(['i18n' => ActivityI18n::tableName()], 'a.id = i18n.id')->where(['i18n.language' => $language])->addSelect([ 'name' => 'i18n.name', 'description' => 'i18n.description']);
        }

        return $query;
    }
}