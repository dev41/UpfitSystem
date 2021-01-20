<?php
namespace app\src\entities\staff;

use app\src\entities\AbstractModel;
use app\src\entities\ISearchModel;
use app\src\entities\place\Place;
use app\src\entities\user\Position;
use app\src\entities\user\UserSearch;
use app\src\library\AccessChecker;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * Class StaffSearch
 */
class StaffSearch extends UserSearch implements ISearchModel
{
    public $positions;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['role_id'], 'safe'],
            [['positions'], 'required'],
            [['birthdayTimeRange'], 'match', 'pattern' => AbstractModel::DATE_TIME_RANGE_PATTERN],
        ]);
    }

    protected function getClubNamesQuery(): Query
    {
        return (new Query())
            ->from(['_spp' => StaffPositionPlace::tableName()])
            ->leftJoin(['_p' => Place::tableName()], '_p.id = _spp.place_id AND _p.type = :place_type', [
                'place_type' => Place::TYPE_CLUB,
            ])
            ->where('user_id = u.id')
            ->groupBy(['_spp.user_id'])
            ->select([
                '_club_names' => 'GROUP_CONCAT(DISTINCT _p.name SEPARATOR ", ")',
            ]);
    }

    protected function getPositionsQuery(): Query
    {
        return (new Query())
            ->from(['_spp' => StaffPositionPlace::tableName()])
            ->leftJoin(['_p' => Position::tableName()], '_p.id = _spp.position_id')
            ->where('user_id = u.id')
            ->groupBy(['_spp.user_id'])
            ->select([
                '_position' => 'GROUP_CONCAT(DISTINCT _p.name SEPARATOR ", ")',
            ]);
    }

    public function getSearchQuery(): Query
    {
        $query = parent::getSearchQuery();

        $query
            ->leftJoin(['spp' => StaffPositionPlace::tableName()], 'spp.user_id = u.id')
            ->leftJoin(['p' => Position::tableName()], 'p.id = spp.position_id')

            ->leftJoin(['place' => Place::tableName()], 'place.id = spp.place_id AND place.type = :place_type', [
                'place_type' => Place::TYPE_CLUB,
            ])
            ->andWhere(['not', ['spp.id' => null]])
            ->addSelect([
                'positionsId' => 'GROUP_CONCAT(DISTINCT p.id)',
                'positions' => $this->getPositionsQuery(),
                'clubIds' => 'GROUP_CONCAT(DISTINCT place.id)',
                'club_names' => $this->getClubNamesQuery(),
                'role_id' => 'u.role_id',
            ])
        ;

        if (!AccessChecker::isSuperAdmin()) {
            $query->leftJoin(['sppp' => StaffPositionPlace::tableName()], 'sppp.place_id = place.id')
                ->andWhere(['sppp.user_id' => \Yii::$app->user->id]);
        }

        return $query;
    }

    public function getSearchDataProvider(array $params = []): ActiveDataProvider
    {
        $dataProvider = parent::getSearchDataProvider($params);

        /** @var Query $query */
        $query = $dataProvider->query;

        if ($this->birthdayTimeRange) {
            $range = explode(' - ', $this->birthdayTimeRange);
            $this->birthdayTimeStart = $range[0];
            $this->birthdayTimeEnd = $range[1];
        }

        $query
            ->andFilterWhere(['=', 'place.id', $this->clubIds])
            ->andFilterWhere(['=', 'p.id', $this->positions])
            ->andFilterWhere(['like', 'u.username', $this->username])
            ->andFilterWhere(['=', 'u.role_id', $this->role_id])
            ->andFilterWhere(['like', 'u.email', $this->email])
            ->andFilterWhere(['like', 'u.phone', $this->phone])
            ->andFilterWhere(['>=', 'u.birthday', $this->birthdayTimeStart])
            ->andFilterWhere(['<=', 'u.birthday', $this->birthdayTimeEnd])
            ->andFilterWhere(['like', 'CONCAT(u.first_name, " ", u.last_name)', $this->fullname]);


        return $dataProvider;
    }

    public function getClubsFilterOptions(): array
    {
        /** @var Query $query */
        $query = $this->getSearchQuery();

        $clubs = $query
            ->select([
                'place.name',
                'place.id',
            ])
            ->distinct()
            ->groupBy(['place.id'])
            ->all();

        return ArrayHelper::map($clubs, 'id', 'name');
    }

    public function getPositionsFilterOptions(): array
    {
        /** @var Query $query */
        $query = $this->getSearchQuery();

        $positions = $query
            ->select([
                'p.name',
                'p.id',
            ])
            ->distinct()
            ->groupBy(['p.id'])
            ->all();

        return ArrayHelper::map($positions, 'id', 'name');
    }

    public function getRolesFilterOptions(): array
    {
        /** @var Query $query */
        $query = $this->getSearchQuery();

        $roles = $query
            ->select([
                'ar.name',
                'u.role_id',
            ])
            ->distinct()
            ->groupBy(['u.role_id'])
            ->all();

        return ArrayHelper::map($roles, 'role_id', 'name');
    }

}