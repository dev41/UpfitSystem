<?php
namespace app\src\entities\news;

use app\src\entities\AbstractModel;
use app\src\entities\image\Image;
use app\src\entities\ISearchModel;
use app\src\entities\place\Place;
use app\src\entities\staff\StaffPositionPlace;
use app\src\library\AccessChecker;
use yii\data\ActiveDataProvider;
use yii\db\Query;

/**
 * Class NewsSearch
 * @inheritdoc
 */
class NewsSearch extends News implements ISearchModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'description'], 'string'],
            [['created_at'], 'safe'],
        ];
    }

    public function getSearchDataProvider(array $params = []): ActiveDataProvider
    {
        $query = (new Query())
            ->from(['n' => self::tableName()])
            ->select([
                'id' => 'n.id',
                'name' => 'n.name',
                'description' => 'n.description',
                'active' => 'n.active',
                'created_at' => 'DATE_FORMAT(n.created_at,\'' . AbstractModel::DATE_LISTING_FORMAT . '\')',
                'images' => 'GROUP_CONCAT(DISTINCT i.file_name)',
            ])->leftJoin(
                ['i' => Image::tableName()],
                'n.id = i.parent_id AND i.type =' . Image::TYPE_NEWS_IMAGE)
            ->groupBy('n.id');

        if (!AccessChecker::isSuperAdmin()) {
            $userId = \Yii::$app->user->id;
            $query->leftJoin(
                ['c' => Place::tableName()],
                'c.id = n.club_id')
                ->leftJoin(
                    ['spp' => StaffPositionPlace::tableName()],
                    'c.id = spp.place_id')
                ->andWhere(['or',
                    ['spp.user_id' => $userId],
                    ['c.created_by' => $userId],
                    ['n.created_by' => $userId],
                ]);
        }

        $this->load($params);

        $query
            ->andFilterWhere([
                'DATE_FORMAT(n.created_at,\'' . AbstractModel::DATE_LISTING_FILTER_FORMAT . '\')' => $this->created_at,
            ])
            ->andFilterWhere(['like', 'n.name', $this->name])
            ->andFilterWhere(['like', 'n.description', $this->description])
        ;

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['attributes' => ['name', 'description']],
            'pagination' => [ 'pageSize' => ISearchModel::BASE_PAGINATION ],
        ]);

        return $dataProvider;
    }
}