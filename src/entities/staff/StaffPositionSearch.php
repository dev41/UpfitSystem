<?php
namespace app\src\entities\staff;

use app\src\entities\ISearchModel;
use app\src\entities\place\Place;
use app\src\entities\user\Position;
use app\src\entities\user\User;
use yii\data\ActiveDataProvider;
use yii\db\Query;

/**
 * Class StaffPositionPlace
 * @inheritdoc
 */
class StaffPositionSearch extends StaffPositionPlace implements ISearchModel
{
    private $staffId;

    public function __construct(int $staffId, array $config = [])
    {
        $this->staffId = $staffId;
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username'], 'string'],
            [['positions'], 'safe'],
        ];
    }

    public function getSearchDataProvider(array $params = []): ActiveDataProvider
    {
        $query = (new Query())
            ->from(['spp' => self::tableName()])
            ->leftJoin(['u' => User::tableName()], 'spp.user_id = u.id')
            ->leftJoin(['pos' => Position::tableName()], 'spp.position_id = pos.id')
            ->leftJoin(['pp' => Place::tableName()], 'spp.place_id = pp.id')
            ->andWhere([
                'u.id' => $this->staffId,
                'u.status' => User::STATUS_ACTIVE,
            ])
            ->groupBy('spp.place_id')
            ->select([
                'club_name' => 'GROUP_CONCAT(DISTINCT pp.name)',
                'username' => 'u.username',
                'positions' => 'GROUP_CONCAT(DISTINCT pos.name SEPARATOR ", ")',
                'club_id' => 'GROUP_CONCAT(DISTINCT spp.place_id)',
                'user_id' => 'GROUP_CONCAT(DISTINCT spp.user_id)'
            ])
        ;
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider;
    }
}