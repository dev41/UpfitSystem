<?php
namespace app\src\entities\place;

use app\src\entities\image\Image;
use app\src\entities\ISearchModel;
use app\src\entities\staff\StaffPositionPlace;
use app\src\entities\user\User;
use app\src\library\AccessChecker;
use yii\data\ActiveDataProvider;
use yii\db\Query;

/**
 * Class ClubSearch
 * @inheritdoc
 */
class ClubSearch extends PlaceSearch implements ISearchModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['owner'], 'safe'],
        ]);
    }

    public function getSearchDataProvider(array $params = []): ActiveDataProvider
    {
        $dataProvider = parent::getSearchDataProvider($params);

        /** @var Query $query */
        $query = $dataProvider->query;

        $query
            ->leftJoin(['spp' => StaffPositionPlace::tableName()], 'spp.place_id = p.id')
            ->leftJoin(['u' => User::tableName()], 'spp.user_id = u.id')
            ->leftJoin(['l' => Image::tableName()], 'l.parent_id = p.id AND l.type =' . Image::TYPE_PLACE_LOGO)
            ->andWhere([
                'p.type' => Place::TYPE_CLUB,
            ])
            ->addSelect([
                'owner' => 'GROUP_CONCAT(DISTINCT u.username SEPARATOR ", ")',
                'created_by' => 'p.created_by',
                'logo' => 'GROUP_CONCAT(DISTINCT l.file_name)',
            ])
            ->groupBy('p.id');

        if (!AccessChecker::isSuperAdmin()) {
            $query->andWhere(['or',
                ['spp.user_id' => \Yii::$app->user->id],
                ['p.created_by' => \Yii::$app->user->id],
            ]);
        }

        return $dataProvider;
    }
}