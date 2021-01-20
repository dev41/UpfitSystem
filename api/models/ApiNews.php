<?php
namespace api\models;

use app\src\entities\customer\CustomerPlace;
use app\src\entities\image\Image;
use app\src\entities\news\News;
use app\src\entities\news\NewsI18n;
use app\src\entities\place\Place;
use yii\db\Query;

class ApiNews extends News
{
    public static function mapApiToDbFields()
    {
        return array_merge(ApiClub::$mapClubApiToDbFields, self::$mapNewsApiToDbFields);
    }

    public static $mapNewsApiToDbFields = [
        '#news_id' => 'n.id',
        '#news_name' => 'n.name',
        '#news_description' => 'n.description',
        '#news_active' => 'n.active',
        '#news_created_at' => 'n.created_at',
        '#news_created_by' => 'n.created_by',
    ];

    public function getNewsQuery($userId, $language)
    {
        $query = $query = (new Query())
            ->from(['n' => News::tableName()])
            ->where(['or',
                ['cp.user_id' => $userId],
                ['c.created_by' => $userId],
                ['n.created_by' => $userId],
            ])
            ->andWhere(['n.active' => true])
            ->leftJoin(['c' => Place::tableName()], 'n.club_id = c.id AND c.type =' . Place::TYPE_CLUB)
            ->leftJoin(['cp' => CustomerPlace::tableName()], 'cp.place_id = c.id')
            ->leftJoin(
                ['i' => Image::tableName()],
                'n.id = i.parent_id AND i.type =' . Image::TYPE_NEWS_IMAGE)
            ->groupBy('n.id')
            ->select([
                'id' => 'n.id',
                'club_id' => 'n.club_id',
                'name' => 'n.name',
                'description' => 'n.description',
                'created_at' => 'n.created_at',
                'image' => 'GROUP_CONCAT(DISTINCT i.file_name)',
            ]);

        if ($language){
            $query->leftJoin(['i18n' => NewsI18n::tableName()], 'n.id = i18n.id')->where(['i18n.language' => $language])->addSelect([ 'name' => 'i18n.name', 'description' => 'i18n.description']);
        }

        return $query;
    }
}