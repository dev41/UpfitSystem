<?php
namespace api\models;

use app\src\entities\image\Image;
use app\src\entities\place\Place;
use app\src\entities\place\PlaceI18n;
use yii\db\Query;

class ApiSubplace extends Place
{
    public static function mapApiToDbFields()
    {
        return array_merge(self::$mapSubplaceApiToDbFields);
    }

    public static $mapSubplaceApiToDbFields = [
        '#place_id' => 'p.id',
        '#place_parent_id' => 'p.parent_id',
        '#place_name' => 'p.name',
        '#place_description' => 'p.description',
        '#place_address' => 'p.address',
        '#place_country' => 'p.country',
        '#place_city' => 'p.city',
        '#place_lat' => 'p.lat',
        '#place_lng' => 'p.lng',
        '#place_active' => 'p.active',
        '#place_created_at' => 'p.created_at',
        '#place_created_by' => 'p.created_by',
        '#place_updated_at' => 'p.updated_at',
        '#place_updated_by' => 'p.updated_by',
    ];

    public function getSubplacesQuery($language)
    {
        $query = (new Query())
            ->from(['p' => $this::tableName()])
            ->where(['!=', 'p.type', $this::TYPE_CLUB])
            ->andWhere(['p.active' => Place::IS_ACTIVE])
            ->leftJoin(['i' => Image::tableName()], 'i.parent_id = p.id AND i.type =' . Image::TYPE_PLACE_PHOTO)
            ->groupBy('p.id')
            ->select([
                'id' => 'p.id',
                'parent_id' => 'p.parent_id',
                'type' => 'p.type',
                'name' => 'p.name',
                'description' => 'p.description',
                'active' => 'p.active',
                'city' => 'p.city',
                'address' => 'p.address',
                'lat' => 'p.lat',
                'lng' => 'p.lng',
                'images' => 'GROUP_CONCAT(i.file_name SEPARATOR ", ")',
            ]);
        if ($language){
            $query->leftJoin(['i18n' => PlaceI18n::tableName()], 'a.id = i18n.id')->where(['i18n.language' => $language])->addSelect([ 'name' => 'i18n.name', 'description' => 'i18n.description']);
        }

        return $query;
    }
}