<?php
namespace app\src\entities\user;

use app\src\entities\AbstractModel;
use app\src\entities\access\AccessRole;
use app\src\entities\ISearchModel;
use yii\data\ActiveDataProvider;
use kartik\daterange\DateRangeBehavior;
use yii\db\Query;

/**
 * Class UserSearch
 * @inheritdoc
 */
class UserSearch extends User implements ISearchModel
{
    public $fullname;
    public $birthdayTimeRange;
    public $birthdayTimeStart;
    public $birthdayTimeEnd;
    public $clubIds;


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

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'fullname', 'email', 'phone', 'birthday', 'description'], 'string'],
            [['created_at', 'clubIds'], 'safe'],
        ];
    }

    public function getSearchQuery(): Query
    {
        $query = (new Query())
            ->from(['u' => self::tableName()])
            ->leftJoin(['ar' => AccessRole::tableName()], 'ar.id = u.role_id')
            ->andWhere(['!=', 'u.status',  User::STATUS_DELETED])
            ->select([
                'id' => 'u.id',
                'username' => 'u.username',
                'role' => 'ar.name',
                'fullname' => 'CONCAT(u.first_name, " ", u.last_name)',
                'email' => 'u.email',
                'phone' => 'u.phone',
                'birthday' => 'u.birthday',
                'description' => 'u.description',
                'created_at' => 'DATE_FORMAT(u.created_at,\'' . AbstractModel::DATE_LISTING_FORMAT . '\')',
            ])
            ->groupBy('u.id')
        ;

        return $query;
    }

    public function getSearchDataProvider(array $params = []): ActiveDataProvider
    {
        $query = $this->getSearchQuery();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['attributes' => ['username', 'fullname', 'email']],
            'pagination' => [ 'pageSize' => ISearchModel::BASE_PAGINATION ],
        ]);

        $this->load($params);

        return $dataProvider;
    }
}