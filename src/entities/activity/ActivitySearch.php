<?php
namespace app\src\entities\activity;

use app\src\entities\AbstractModel;
use app\src\entities\image\Image;
use app\src\entities\ISearchModel;
use app\src\entities\place\Place;
use app\src\entities\staff\StaffPositionPlace;
use app\src\entities\user\UserActivity;
use app\src\library\AccessChecker;
use yii\data\ActiveDataProvider;
use yii\db\Query;

/**
 * Class ActivitySearch
 * @inheritdoc
 */
class ActivitySearch extends Activity implements ISearchModel
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
            ->from(['a' => self::tableName()])
            ->select([
                'id' => 'a.id',
                'club_id' => 'a.club_id',
                'name' => 'a.name',
                'description' => 'a.description',
                'price' => 'a.price',
                'capacity' => 'a.capacity',
                'start' => 'DATE_FORMAT(a.start,\'' . AbstractModel::DATE_LISTING_FORMAT . '\')',
                'end' => 'DATE_FORMAT(a.end,\'' . AbstractModel::DATE_LISTING_FORMAT . '\')',
                'created_at' => 'DATE_FORMAT(a.created_at,\'' . AbstractModel::DATE_LISTING_FORMAT . '\')',
                'images' => 'GROUP_CONCAT(DISTINCT i.file_name)',
            ])->leftJoin(
                ['i' => Image::tableName()],
                'a.id = i.parent_id AND i.type =' . Image::TYPE_ACTIVITY_IMAGE)
            ->groupBy('a.id');

        if (!AccessChecker::isSuperAdmin()) {
            $userId = \Yii::$app->user->id;
            $query->leftJoin(
                ['c' => Place::tableName()],
                'c.id = a.club_id')
                ->leftJoin(
                    ['spp' => StaffPositionPlace::tableName()],
                    'c.id = spp.place_id')
                ->leftJoin(
                    ['ua' => UserActivity::tableName()],
                    'ua.activity_id = a.id')
                ->andWhere(['or',
                    ['spp.user_id' => $userId],
                    ['c.created_by' => $userId],
                    ['a.created_by' => $userId],
                    ['ua.user_id' => $userId],
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
                'DATE_FORMAT(a.created_at,\''. AbstractModel::DATE_LISTING_FILTER_FORMAT . '\')' => $this->created_at,
            ])
            ->andFilterWhere([
                '>=', 'DATE_FORMAT(a.start,\'' . AbstractModel::DATE_LISTING_FILTER_FORMAT . '\')', $this->startTimeBegin,
            ])
            ->andFilterWhere([
                '<=', 'DATE_FORMAT(a.start,\'' . AbstractModel::DATE_LISTING_FILTER_FORMAT . '\')', $this->startTimeEnd,
            ])
            ->andFilterWhere(['like', 'a.name', $this->name])
            ->andFilterWhere(['like', 'a.description', $this->description])
            ->andFilterWhere(['=', 'a.capacity', $this->capacity])
            ->andFilterWhere(['=', 'a.price', $this->price])
        ;

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['attributes' => ['name', 'description', 'price', 'capacity']],
            'pagination' => [ 'pageSize' => ISearchModel::BASE_PAGINATION ],
        ]);

        return $dataProvider;
    }
}