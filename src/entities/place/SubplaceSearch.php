<?php
namespace app\src\entities\place;

use app\src\entities\ISearchModel;
use app\src\entities\staff\StaffPositionPlace;
use app\src\entities\user\User;
use app\src\library\AccessChecker;
use yii\data\ActiveDataProvider;
use yii\db\Query;

/**
 * Class SubplaceSearch
 * @inheritdoc
 */
class SubplaceSearch extends PlaceSearch implements ISearchModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['parent_id'], 'integer'],
        ]);
    }

    public function getSearchDataProvider(array $params = []): ActiveDataProvider
    {
        $dataProvider = parent::getSearchDataProvider($params);

        /** @var Query $query */
        $query = $dataProvider->query;

        $query
            ->leftJoin(['pp' => self::tableName()], 'pp.id = p.parent_id')
            ->leftJoin(['spp' => StaffPositionPlace::tableName()], 'pp.id = spp.place_id')
            ->andWhere(['<>', 'p.type', Place::TYPE_CLUB])
            ->addSelect([
                'parent_id' => 'p.parent_id',
                'club_name' => 'pp.name',
            ])
            ->groupBy('p.id')
        ;

        if (!AccessChecker::isSuperAdmin()) {
            $query
                ->leftJoin(['u' => User::tableName()], 'spp.user_id = u.id')
                ->andWhere(['u.id' => \Yii::$app->user->id])
                ->orWhere(['p.created_by' => \Yii::$app->user->id])
            ;
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['attributes' => ['name', 'type', 'description', 'address']],
            'pagination' => [ 'pageSize' => ISearchModel::BASE_PAGINATION ],

        ]);

        return $dataProvider;
    }
}