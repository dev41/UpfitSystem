<?php
namespace app\src\entities\customer;

use app\src\entities\AbstractModel;
use app\src\entities\ISearchModel;
use app\src\entities\user\User;
use yii\data\ActiveDataProvider;
use yii\db\Query;

/**
 * Class CustomerPlaceSearch
 * @inheritdoc
 */
class CustomerPlaceSearch extends CustomerPlace implements ISearchModel
{
    private $clubId;

    public function __construct(int $clubId, array $config = [])
    {
        $this->clubId = $clubId;
        parent::__construct($config);
    }

    public function getSearchDataProvider(array $params = []): ActiveDataProvider
    {
        $query = (new Query())
            ->from(['cp' => self::tableName()])
            ->leftJoin(['u' => User::tableName()], 'cp.user_id = u.id')
            ->andWhere([
                'cp.place_id' => $this->clubId,
                'u.status' => User::STATUS_ACTIVE,
            ])
            ->select([
                'username' => 'u.username',
                'fullname' => 'CONCAT(u.first_name, " ", u.last_name)',
                'email' => 'u.email',
                'card_number' => 'cp.card_number',
                'phone' => 'u.phone',
                'birthday' => 'u.birthday',
                'description' => 'u.description',
                'created_at' => 'DATE_FORMAT(u.created_at,\'' . AbstractModel::DATE_LISTING_FORMAT . '\')',
                'club_id' => 'cp.place_id',
                'user_id' => 'cp.user_id',
                'status' => 'cp.status'
            ])
        ;

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider;
    }

}