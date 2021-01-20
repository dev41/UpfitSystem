<?php
namespace app\src\entities\trigger;

use app\src\entities\ISearchModel;
use app\src\entities\place\Club;
use app\src\library\AccessChecker;
use app\src\library\Query;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

class TriggerSearch extends Trigger implements ISearchModel
{
    public function getSearchDataProvider(array $params = []): ActiveDataProvider
    {
        $clubs = ArrayHelper::map(Club::getClubsByUserId(), 'id', 'id');
        $query = (new Query())
            ->from(['t' => self::tableName()])
            ->leftJoin(['tt' => TriggerType::tableName()], 'tt.trigger_id = t.id')
            ->leftJoin(['tp' => TriggerPlace::tableName()], 'tp.trigger_id = t.id')
            ->select([
                'id' => 't.id',
                'name' => 't.name',
                'types' => 'GROUP_CONCAT(DISTINCT tt.type SEPARATOR ", ")',
                'priority' => 'GROUP_CONCAT(DISTINCT tt.priority SEPARATOR ", ")',
                'event' => 't.event',
                'template' => 't.template',
            ])
        ->andWhere(['!=', 't.is_newsletter', Newsletter::IS_NEWSLETTER])
        ->groupBy('t.id');

        if (!AccessChecker::isSuperAdmin()) {
            $query
                ->leftJoin(['tc' => TriggerPlace::tableName()], 'tp.trigger_id = t.id and tp.type = ' . TriggerPlace::TYPE_PARENT_CLUB)
                ->andWhere(['tc.place_id' => $clubs]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['attributes' => ['name', 'types', 'event']],
            'pagination' => [ 'pageSize' => ISearchModel::BASE_PAGINATION ],
        ]);

        return $dataProvider;
    }
}