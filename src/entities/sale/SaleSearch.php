<?php
namespace app\src\entities\sale;

use app\src\entities\AbstractModel;
use app\src\entities\image\Image;
use app\src\entities\ISearchModel;
use app\src\entities\place\Place;
use app\src\entities\staff\StaffPositionPlace;
use app\src\library\AccessChecker;
use yii\data\ActiveDataProvider;
use yii\db\Query;

/**
 * Class ActivitySearch
 * @inheritdoc
 */
class SaleSearch extends Sale implements ISearchModel
{
    public $startTimeRange;
    public $startTimeBegin;
    public $startTimeEnd;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['startTimeRange'], 'match', 'pattern' => AbstractModel::DATE_TIME_RANGE_PATTERN],
        ]);
    }

    public function getSearchDataProvider(array $params = []): ActiveDataProvider
    {
        $query = (new Query())
            ->from(['s' => self::tableName()])
            ->select([
                'id' => 's.id',
                'club_id' => 's.club_id',
                'name' => 's.name',
                'description' => 's.description',
                'start' => 'DATE_FORMAT(s.start,\'' . AbstractModel::DATE_LISTING_FORMAT . '\')',
                'end' => 'DATE_FORMAT(s.end,\'' . AbstractModel::DATE_LISTING_FORMAT .'\')',
                'created_at' => 'DATE_FORMAT(s.created_at,\'' . AbstractModel::DATE_LISTING_FORMAT . '\')',
                'images' => 'GROUP_CONCAT(DISTINCT i.file_name)',
            ])->leftJoin(
                ['i' => Image::tableName()],
                's.id = i.parent_id AND i.type =' . Image::TYPE_SALE_IMAGE)
            ->groupBy('s.id');

        if (!AccessChecker::isSuperAdmin()) {
            $userId = \Yii::$app->user->id;
            $query->leftJoin(
                ['c' => Place::tableName()],
                'c.id = s.club_id')
                ->leftJoin(
                    ['spp' => StaffPositionPlace::tableName()],
                    'c.id = spp.place_id')
                ->andWhere(['or',
                    ['spp.user_id' => $userId],
                    ['c.created_by' => $userId],
                    ['s.created_by' => $userId],
                ]);
        }

        $this->load($params);

        if ($this->startTimeRange) {
            $range = explode(' - ', $this->startTimeRange);
            $this->startTimeBegin = $range[0];
            $this->startTimeEnd = $range[1];
        }

        $query
            ->andFilterWhere([
                'DATE_FORMAT(s.created_at,\'' . AbstractModel::DATE_LISTING_FILTER_FORMAT . '\')' => $this->created_at,
            ])
            ->andFilterWhere([
                '>=', 'DATE_FORMAT(s.start,\'' . AbstractModel::DATE_LISTING_FILTER_FORMAT . '\')', $this->startTimeBegin,
            ])
            ->andFilterWhere([
                '<=', 'DATE_FORMAT(s.start,\'' . AbstractModel::DATE_LISTING_FILTER_FORMAT . '\')', $this->startTimeEnd,
            ])
            ->andFilterWhere(['like', 's.name', $this->name])
            ->andFilterWhere(['like', 's.description', $this->description])
        ;

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['attributes' => ['name', 'description']],
            'pagination' => [ 'pageSize' => ISearchModel::BASE_PAGINATION ],
        ]);

        return $dataProvider;
    }
}