<?php
namespace app\src\entities\access;

use app\src\entities\ISearchModel;
use app\src\entities\place\PlaceAccessRole;
use app\src\entities\staff\StaffPositionPlace;
use app\src\library\AccessChecker;
use yii\data\ActiveDataProvider;
use yii\db\Query;

/**
 * Class AccessRoleSearch
 */
class AccessRoleSearch extends AccessRole implements ISearchModel
{
    public function getSearchDataProvider(array $params = []): ActiveDataProvider
    {
        $dataProvider = new ActiveDataProvider([
            'query' => AccessRole::find(),
            'sort' => ['attributes' => ['name', 'type', 'slug']],
            'pagination' => [ 'pageSize' => ISearchModel::BASE_PAGINATION ],
        ]);

        if (!AccessChecker::isSuperAdmin()) {

            /** @var Query $query */
            $query = $dataProvider->query;
            $query
                ->leftJoin(['par' => PlaceAccessRole::tableName()], 'par.access_role_id = access_role.id')
                ->leftJoin(['spp' => StaffPositionPlace::tableName()] , 'spp.place_id = par.place_id')
                ->andwhere([
                    'spp.user_id' => \Yii::$app->user->getId(),
                    'access_role.type' => AccessRole::TYPE_DEFAULT
                ])->orWhere(['par.access_role_id' => null])
                ->andWhere(['!=', 'access_role.slug', AccessRole::ROLE_SUPER_ADMIN])
                ->groupBy('access_role.id');
        }

        return $dataProvider;
    }
}