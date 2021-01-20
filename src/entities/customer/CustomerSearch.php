<?php
namespace app\src\entities\customer;

use app\src\entities\AbstractModel;
use app\src\entities\ISearchModel;
use app\src\entities\place\Place;
use app\src\entities\staff\StaffPositionPlace;
use app\src\entities\user\UserSearch;
use app\src\library\AccessChecker;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * Class CustomerSearch
 */
class CustomerSearch extends UserSearch implements ISearchModel
{
    public $card_number;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['card_number'], 'string'],
            [['birthdayTimeRange'], 'match', 'pattern' => AbstractModel::DATE_TIME_RANGE_PATTERN],
        ]);
    }

    protected function getClubNamesQuery(): Query
    {
        return (new Query())
            ->from(['_cp' => CustomerPlace::tableName()])
            ->leftJoin(['_p' => Place::tableName()], '_p.id = _cp.place_id AND _p.type = :place_type', [
                'place_type' => Place::TYPE_CLUB,
            ])
            ->where('user_id = u.id')
            ->groupBy(['_cp.user_id'])
            ->select([
                '_club_names' => 'GROUP_CONCAT(DISTINCT _p.name SEPARATOR ", ")',
            ])
            ;
    }

    public function getSearchQuery(): Query
    {
        $query = parent::getSearchQuery();

        $query
            ->leftJoin(['cp' => CustomerPlace::tableName()], 'cp.user_id = u.id')
            ->leftJoin(['place' => Place::tableName()], 'place.id = cp.place_id AND place.type = :place_type', [
                'place_type' => Place::TYPE_CLUB,
            ])
            ->addSelect([
                'card_number' => 'GROUP_CONCAT(DISTINCT cp.card_number)',
                'club_names' => $this->getClubNamesQuery(),
            ])
            ->andWhere(['not', ['cp.place_id' => null]]);

        if (!AccessChecker::isSuperAdmin()) {
            $userId = \Yii::$app->user->id;
            $query->leftJoin(
                ['spp' => StaffPositionPlace::tableName()],
                'spp.place_id = place.id'
            )->andWhere(['or',
                ['spp.user_id' => $userId],
                ['u.created_by' => $userId],
            ]);
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
            ->andFilterWhere(['like', 'u.username', $this->username])
            ->andFilterWhere(['=', 'place.id', $this->clubIds])
            ->andFilterWhere(['like', 'CONCAT(u.first_name, " ", u.last_name)', $this->fullname])
            ->andFilterWhere(['like', 'u.email', $this->email])
            ->andFilterWhere(['like', 'u.phone', $this->phone])
            ->andFilterWhere(['like', 'cp.card_number', $this->card_number])
            ->andFilterWhere(['>=', 'u.birthday', $this->birthdayTimeStart])
            ->andFilterWhere(['<=', 'u.birthday', $this->birthdayTimeEnd]);

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
}