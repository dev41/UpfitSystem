<?php
namespace app\src\entities\access;

use app\src\entities\ISearchModel;
use yii\data\ActiveDataProvider;

/**
 * Class AccessControlSearch
 */
class AccessControlSearch extends AccessControl implements ISearchModel
{
    public function getSearchDataProvider(array $params = [], $controlType = self::TYPE_CONTROLLER): ActiveDataProvider
    {
        $query = AccessControl::find()
            ->alias('ac')
            ->andWhere([
                'ac.type' => $controlType,
                'ac.access_type' => AccessControl::ACCESS_TYPE_PERMISSION,
            ])
            ->orderBy('ac.parent_id')
        ;

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider;
    }
}