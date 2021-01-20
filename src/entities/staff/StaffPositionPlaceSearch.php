<?php
namespace app\src\entities\staff;

use app\src\entities\AbstractModel;
use app\src\entities\ISearchModel;
use app\src\entities\place\Place;
use app\src\entities\user\Position;
use app\src\entities\user\User;
use yii\data\ActiveDataProvider;
use kartik\daterange\DateRangeBehavior;
use yii\helpers\ArrayHelper;
use yii\db\Query;

/**
 * Class StaffPositionPlace
 * @inheritdoc
 */
class StaffPositionPlaceSearch extends StaffPositionPlace implements ISearchModel
{
    public $username;
    public $phone;
    public $email;
    public $address;
    public $fullname;
    public $description;
    public $birthdayTimeRange;
    public $birthdayTimeStart;
    public $birthdayTimeEnd;
    private $clubId;

    public function behaviors()
    {
        return [
            [
                'class' => DateRangeBehavior::class,
                'attribute' => 'birthdayTimeRange',
                'dateStartAttribute' => 'birthdayTimeStart',
                'dateEndAttribute' => 'birthdayTimeEnd',
            ]
        ];
    }

    public function __construct(int $clubId, array $config = [])
    {
        $this->clubId = $clubId;
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'fullname', 'email', 'phone', 'address', 'birthday', 'description'], 'string'],
            [['positions'], 'safe'],
            [['birthdayTimeRange'], 'match', 'pattern' => AbstractModel::DATE_TIME_RANGE_PATTERN],
        ];
    }

    protected function getPositionsQuery(): Query
    {
        return (new Query())
            ->from(['_spp' => self::tableName()])
            ->leftJoin(['_p' => Position::tableName()], '_p.id = _spp.position_id')
            ->where('user_id = u.id')
            ->groupBy(['_spp.user_id'])
            ->select([
                '_position' => 'GROUP_CONCAT(DISTINCT _p.name SEPARATOR ", ")',
            ]);
    }

    public function getSearchQuery(): Query
    {
        $query = (new Query())
            ->from(['spp' => self::tableName()])
            ->leftJoin(['u' => User::tableName()], 'spp.user_id = u.id')
            ->leftJoin(['pos' => Position::tableName()], 'spp.position_id = pos.id')
            ->leftJoin(['pp' => Place::tableName()], 'spp.place_id = pp.id')
            ->andWhere([
                'spp.place_id' => $this->clubId,
                'u.status' => User::STATUS_ACTIVE,
            ])
            ->groupBy('spp.user_id')
            ->select([
                'id' => 'u.id',
                'club_name' => 'GROUP_CONCAT(DISTINCT pp.name)',
                'fullname' => 'CONCAT(u.first_name, " ", u.last_name)',
                'username' => 'u.username',
                'email' => 'u.email',
                'phone' => 'u.phone',
                'birthday' => 'u.birthday',
                'address' => 'u.address',
                'description' => 'u.description',
                'positions' => $this->getPositionsQuery(),
                'club_id' => 'GROUP_CONCAT(DISTINCT spp.place_id)',
                'user_id' => 'GROUP_CONCAT(DISTINCT spp.user_id)'
            ]);
        return $query;
    }

    public function getSearchDataProvider(array $params = []): ActiveDataProvider
    {
        $query = $this->getSearchQuery();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['attributes' => ['username', 'fullname', 'email', 'positions',
                'phone', 'birthday', 'address', 'description', 'positions']],
            'pagination' => ['pageSize' => ISearchModel::BASE_PAGINATION],
        ]);

        $this->load($params);

        if ($this->birthdayTimeRange) {
            $range = explode(' - ', $this->birthdayTimeRange);
            $this->birthdayTimeStart = $range[0];
            $this->birthdayTimeEnd = $range[1];
        }
        $query->andFilterWhere(['=', 'pos.id', $this->positions])
            ->andFilterWhere(['like', 'u.username', $this->username])
            ->andFilterWhere(['like', 'u.email', $this->email])
            ->andFilterWhere(['like', 'u.phone', $this->phone])
            ->andFilterWhere(['like', 'u.address', $this->address])
            ->andFilterWhere(['like', 'u.description', $this->description])
            ->andFilterWhere(['>=', 'u.birthday', $this->birthdayTimeStart])
            ->andFilterWhere(['<=', 'u.birthday', $this->birthdayTimeEnd])
            ->andFilterWhere(['like', 'CONCAT(u.first_name, " ", u.last_name)', $this->fullname]);

        return $dataProvider;
    }


    public function getPositionsFilterOptions(): array
    {
        /** @var Query $query */
        $query = $this->getSearchQuery();

        $positions = $query
            ->select([
                'pos.name',
                'pos.id',
            ])
            ->distinct()
            ->groupBy(['pos.id'])
            ->all();

        return ArrayHelper::map($positions, 'id', 'name');
    }

}