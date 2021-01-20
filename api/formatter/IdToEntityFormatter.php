<?php
namespace api\formatter;

use app\src\entities\AbstractModel;
use yii\db\Query;

class IdToEntityFormatter
{
    /**
     * @param array $modelsData
     * @param string|AbstractModel $modelClass
     * @param string $fieldName
     * @param callable [$queryFormatter]
     * @return array
     */
    public static function format(
        array $modelsData,
        $modelClass,
        string $fieldName,
        callable $queryFormatter = null
    ): array
    {
        $modelIds = [];
        foreach ($modelsData as $modelDataKey => $model) {
            $modelsData[$modelDataKey][$fieldName] = array_filter(explode(',', (string) $model[$fieldName]));
            if (!$model[$fieldName]) {
                continue;
            }
            $modelIds = array_merge($modelIds, $modelsData[$modelDataKey][$fieldName]);
        }

        $modelIds = array_unique($modelIds);

        /** @var Query $query */
        $query = $modelClass::find()
            ->select($modelClass::tableName() . '.*')
            ->groupBy($modelClass::tableName() . '.id')
            ->where([$modelClass::tableName() . '.id' => $modelIds])->asArray();

        if (is_callable($queryFormatter)) {
            $query = $queryFormatter($query);
        }

        /** @var AbstractModel[] $relationModels */
        $relationModels = $query->all();

        $indexesRelationModels = [];
        foreach ($relationModels as $model) {
            $indexesRelationModels[$model['id']] = $model;
        }

        foreach ($modelsData as $modelDataKey => $model) {
            if (!empty($model[$fieldName])) {
                foreach ($model[$fieldName] as $modelIdKey => $modelId) {
                    $modelsData[$modelDataKey][$fieldName][$modelIdKey] = $indexesRelationModels[$modelId];
                }
            }
        }

        return $modelsData;
    }
}
