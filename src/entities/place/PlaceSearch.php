<?php
namespace app\src\entities\place;

use app\src\entities\AbstractModel;
use app\src\entities\image\Image;
use app\src\entities\ISearchModel;
use yii\data\ActiveDataProvider;
use yii\db\Query;

/**
 * Class PlaceSearch
 * @inheritdoc
 */
class PlaceSearch extends Place implements ISearchModel
{
    public $owner;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'address', 'description'], 'string'],
            [['created_at'], 'safe'],
        ];
    }

    public function getSearchDataProvider(array $params = []): ActiveDataProvider
    {
        $query = (new Query())
            ->from(['p' => self::tableName()])
            ->leftJoin(['i' => Image::tableName()], 'i.parent_id = p.id AND i.type =' . Image::TYPE_PLACE_PHOTO)
            ->select([
                'id' => 'p.id',
                'name' => 'p.name',
                'type' => 'p.type',
                'city' => 'p.city',
                'address' => 'p.address',
                'image' => 'GROUP_CONCAT(DISTINCT i.file_name)',
                'description' => 'p.description',
                'created_at' => 'DATE_FORMAT(p.created_at,\'' . AbstractModel::DATE_LISTING_FORMAT . '\')',
            ])
        ;

        $this->load($params);

        $query
            ->andFilterWhere([
                'DATE_FORMAT(p.created_at,\'' . AbstractModel::DATE_LISTING_FILTER_FORMAT . '\')' => $this->created_at,
            ])
            ->andFilterWhere(['like', 'p.address', $this->address])
            ->andFilterWhere(['like', 'p.name', $this->name])
            ->andFilterWhere(['like', 'p.description', $this->description])
            ->andFilterWhere(['pp.id' => $this->parent_id]);
        ;

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['attributes' => ['name', 'city']],
            'pagination' => [ 'pageSize' => ISearchModel::BASE_PAGINATION ],
        ]);

        return $dataProvider;
    }
}