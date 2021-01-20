<?php
namespace app\src\entities\user;

use app\src\entities\AbstractModel;
use app\src\entities\coaching\Event;
use app\src\entities\ISearchModel;
use yii\data\ActiveDataProvider;
use yii\db\Query;

/**
 * Class UserEventSearch
 * @inheritdoc
 */
class UserEventSearch extends UserEvent implements ISearchModel
{
    private $event_id;

    public function __construct(int $eventId, array $config = [])
    {
        $this->event_id= $eventId;
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'fullname', 'email', 'phone', 'birthday', 'description'], 'string'],
            [['status'], 'int'],
            [['created_at'], 'safe'],
        ];
    }

    public function getSearchDataProvider(array $params = []): ActiveDataProvider
    {
        $query = (new Query())
            ->from(['ue' => self::tableName()])
            ->leftJoin(['u' => User::tableName()], 'ue.user_id = u.id')
            ->leftJoin(['e' => Event::tableName()], 'e.id = ue.event_id')
            ->where([
                'ue.event_id' => $this->event_id,
            ])
            ->groupBy('u.id')
            ->select([
                'id' => 'e.id',
                'username' => 'u.username',
                'fullname' => 'CONCAT(u.first_name, " ", u.last_name)',
                'email' => 'u.email',
                'phone' => 'u.phone',
                'birthday' => 'u.birthday',
                'description' => 'u.description',
                'created_at' => 'DATE_FORMAT(u.created_at,\'' . AbstractModel::DATE_LISTING_FORMAT . '\')',
            ])
        ;

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider;
    }

}