<?php
namespace app\src\entities\notification;

use app\src\entities\AbstractModel;
use app\src\entities\ISearchModel;
use app\src\entities\trigger\Trigger;
use app\src\entities\user\User;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * Class WebNotificationSearch
 * @inheritdoc
 */
class WebNotificationSearch extends WebNotification implements ISearchModel
{
    public function getSearchQuery(): Query
    {
        $query = (new Query())
            ->from(['wn' => self::tableName()])
            ->leftJoin(['u' => User::tableName()], 'wn.user_id = u.id')
            ->where([
                'user_id' => \Yii::$app->user->getId(),
            ])
            ->select([
                'id' => 'wn.id',
                'user_id' => 'u.id',
                'username' => 'COALESCE(u.username, u.email, u.first_name, u.fb_user_id)',
                'event' => 'wn.event',
                'message' => 'wn.message',
                'status' => 'wn.status',
                'created_at' => 'wn.created_at',
            ]);

        return $query;
    }

    public function getSearchDataProvider(array $params = []): ActiveDataProvider
    {
        /** @var Query $query */
        $query = $this->getSearchQuery();

        $this->load($params);

        $query
            ->andFilterWhere([
                'DATE_FORMAT(wn.created_at,\'' . AbstractModel::DATE_LISTING_FILTER_FORMAT . '\')' => $this->created_at,
            ])
            ->andFilterWhere(['=', 'wn.event', $this->event])
            ->andFilterWhere(['=', 'wn.status', $this->status])
            ->andFilterWhere(['like', 'wn.message', $this->message])
        ;

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['attributes' => ['status', 'message']],
            'pagination' => [ 'pageSize' => ISearchModel::BASE_PAGINATION ],
        ]);

        return $dataProvider;
    }

    public function getEventsFilterOptions(): array
    {
        /** @var Query $query */
        $query = $this->getSearchQuery();

        $events = $query
            ->select([
                'wn.event',
            ])
            ->distinct()
            ->all();

        $eventLabels = [];
        $events = ArrayHelper::map($events, 'event', 'event');

        foreach ($events as $event) {
            $eventLabels[$event] = Trigger::EVENT_LABELS[$event];
        }

        return $eventLabels;
    }

    public function getStatusFilterOptions(): array
    {
        /** @var Query $query */
        $query = $this->getSearchQuery();

        $statusIds = $query
            ->select([
                'wn.status',
            ])
            ->distinct()
            ->all();

        $statusLabels = [];
        $statusIds = ArrayHelper::map($statusIds, 'status', 'status');

        foreach ($statusIds as $status) {
            $statusLabels[$status] = Notification::STATUS_LABELS[$status];
        }

        return $statusLabels;
    }

}