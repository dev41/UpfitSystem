<?php
namespace app\src\entities\coaching;

use app\src\entities\AbstractModel;
use app\src\entities\image\Image;
use app\src\entities\ISearchModel;
use app\src\entities\place\Place;
use app\src\entities\staff\StaffPositionPlace;
use app\src\entities\user\User;
use app\src\entities\user\UserCoaching;
use app\src\library\AccessChecker;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * Class CoachingSearch
 */
class CoachingSearch extends Coaching implements ISearchModel
{
    public $clubIds;
    public $placeIds;
    public $trainerIds;
    public $filterCapacity;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['clubIds', 'placeIds', 'trainerIds'], 'safe'],
            [['filterCapacity'], 'integer'],
            [['createTimeRange'], 'match', 'pattern' => AbstractModel::DATE_TIME_RANGE_PATTERN],
        ]);
    }

    protected function getClubNamesQuery(): Query
    {
        return (new Query())
            ->from(['_club' => Place::tableName()])
            ->leftJoin(['_cp' => CoachingPlace::tableName()], '_cp.place_id = _club.id AND _club.type = :place_type', [
                'place_type' => Place::TYPE_CLUB,
            ])
            ->where('_cp.coaching_id = c.id')
            ->select([
                '_club_names' => 'GROUP_CONCAT(DISTINCT _club.name SEPARATOR ", ")',
            ]);
    }

    protected function getPlaceNamesQuery(): Query
    {
        return (new Query())
            ->from(['_p' => Place::tableName()])
            ->leftJoin(['_cp' => CoachingPlace::tableName()], '_cp.place_id = _p.id AND _p.type != :place_type', [
                'place_type' => Place::TYPE_CLUB,
            ])
            ->where('_cp.coaching_id = c.id')
            ->select([
                '_place_names' => 'GROUP_CONCAT(DISTINCT _p.name SEPARATOR ", ")',
            ]);
    }

    protected function getTrainerNamesQuery(): Query
    {
        return (new Query())
            ->from(['_u' => User::tableName()])
            ->leftJoin(['_uc' => UserCoaching::tableName()], '_uc.user_id = _u.id')
            ->where('_uc.coaching_id = c.id')
            ->select([
                '_trainerNames' => 'GROUP_CONCAT(DISTINCT _u.username SEPARATOR ", ")',
            ]);
    }

    public function getSearchQuery(array $params = []): Query
    {
        $query = (new Query())
            ->from(['c' => self::tableName()])
            ->leftJoin(['uc' => UserCoaching::tableName()], 'uc.coaching_id = c.id')
            ->leftJoin(['u' => User::tableName()], 'u.id = uc.user_id')
            ->leftJoin(['cl' => CoachingLevel::tableName()], 'c.coaching_level_id = cl.id')
            ->leftJoin(['e' => Event::tableName()], 'e.coaching_id = c.id')
            ->leftJoin(['cp' => CoachingPlace::tableName()], 'cp.coaching_id = c.id')
            ->leftJoin(['club' => Place::tableName()], 'cp.place_id = club.id AND club.type = :place_type',
                ['place_type' => Place::TYPE_CLUB]
            )->leftJoin(
                ['p' => Place::tableName()],
                'cp.place_id = p.id AND (p.type != :club)',
                [
                    'club' => Place::TYPE_CLUB,
                ]
            )
            ->leftJoin(
                ['i' => Image::tableName()],
                'c.id = i.parent_id AND i.type =' . Image::TYPE_COACHING_IMAGE)
            ->andWhere(['e.id' => null])
            ->select([
                'id' => 'c.id',
                'name' => 'c.name',
                'clubIds' => 'GROUP_CONCAT(club.id SEPARATOR ", ")',
                'clubNames' =>  $this->getClubNamesQuery(),
                'placeIds' => 'GROUP_CONCAT(p.id SEPARATOR ", ")',
                'placeNames' => $this->getPlaceNamesQuery(),
                'trainerIds' => 'GROUP_CONCAT(DISTINCT u.id)',
                'trainerNames' => $this->getTrainerNamesQuery(),
                'description' => 'c.description',
                'price' => 'c.price',
                'capacity' => 'c.capacity',
                'image' => 'GROUP_CONCAT(DISTINCT i.file_name)',
                'color' => 'c.color',
                'level' => 'cl.name',
            ])
            ->groupBy('c.id');

        if (!empty($params['clubId'])) {
            $query->andWhere(['club.id' => $params['clubId']]);
        }

        if (!empty($params['coachingId'])) {
            $query->andWhere(['c.id' => (int) $params['coachingId']]);
        }

        if (!AccessChecker::isSuperAdmin()) {
            $userId = \Yii::$app->user->id;
            $query->leftJoin(
                ['spp' => StaffPositionPlace::tableName()],
                'club.id = spp.place_id'
            )->andWhere(['or',
                ['spp.user_id' => $userId],
                ['club.created_by' => $userId],
                ['c.created_by' => $userId],
            ]);
        }

        return $query;
    }

    public function getSearchDataProvider(array $params = []): ActiveDataProvider
    {
        /** @var Query $query */
        $query = $this->getSearchQuery($params);

        $this->load($params);

        $query
            ->andFilterWhere(['like', 'c.name', $this->name])
            ->andFilterWhere(['=', 'c.price', $this->price])
            ->andFilterWhere(['like', 'cl.name', $this->level])
            ->andFilterWhere(['=', 'club.id', $this->clubIds])
            ->andFilterWhere(['=', 'p.id', $this->placeIds])
            ->andFilterWhere(['like', 'c.description', $this->description])
            ->andFilterWhere(['=', 'c.capacity', $this->filterCapacity])
            ->andFilterWhere(['=', 'u.id', $this->trainerIds]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['attributes' => ['name', 'level', 'description', 'price']],
            'pagination' => [ 'pageSize' => ISearchModel::BASE_PAGINATION ],
        ]);

        return $dataProvider;
    }

    public function getClubsFilterOptions(): array
    {
        /** @var Query $query */
        $query = $this->getSearchQuery();

        $clubs = $query
            ->select([
                'club.name',
                'club.id',
            ])
            ->where(['not', ['club.id' => null]])
            ->distinct()
            ->groupBy(['club.id'])
            ->all();

        return ArrayHelper::map($clubs, 'id', 'name');
    }

    public function getPlacesFilterOptions(): array
    {
        /** @var Query $query */
        $query = $this->getSearchQuery();

        $places = $query
            ->select([
                'p.name',
                'p.id',
            ])
            ->where(['not', ['p.id' => null]])
            ->distinct()
            ->groupBy(['p.id'])
            ->all();

        return ArrayHelper::map($places, 'id', 'name');
    }

    public function getTrainersFilterOptions(): array
    {
        /** @var Query $query */
        $query = $this->getSearchQuery();

        $trainers = $query
            ->select([
                'name_label' => 'COALESCE(u.username, u.email, u.first_name, u.fb_user_id)',
                'u.id',
            ])
            ->where(['not', ['u.id' => null]])
            ->distinct()
            ->groupBy(['u.id'])
            ->all();

        return ArrayHelper::map($trainers, 'id', 'name_label');
    }
}