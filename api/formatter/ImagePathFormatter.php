<?php

namespace api\formatter;

use app\src\entities\AbstractModel;
use app\src\helpers\ImageHelper;
use app\src\helpers\UrlHelper;

class ImagePathFormatter
{
    const LOGO_SUB_DIR = '/logo';

    public static function getFullUrl(AbstractModel $model, $filename, string $extPath)
    {
        return UrlHelper::getAbsoluteBaseUrl() . ImageHelper::getUrl($model, $filename, $extPath);
    }

    /**
     * @param array $modelsData
     * @param string|AbstractModel $modelClass
     * @param array $option
     * @return array
     */
    public static function format(array $modelsData, $modelClass, array $option): array
    {
        if (empty($modelsData)) {
            return $modelsData;
        }

        $fieldName = $option['fieldName'] ?? 'logo';
        $fieldId = $option['fieldId'] ?? 'id';
        $extPath = $option['extPath'] ?? '';
        $isDataTypeArray = $option['isDataTypeArray'] ?? false;

        foreach ($modelsData as $dataKey => $data) {
            if ($data[$fieldName]) {
                $model = $modelClass::findOne($data[$fieldId]);
                $fileNames = explode(', ', $data[$fieldName]);
                $imagesUrls = [];
                foreach ($fileNames as $fileName) {
                    $imagesUrls[] = self::getFullUrl($model, $fileName, $extPath);
                }
                $modelsData[$dataKey][$fieldName] = $isDataTypeArray
                    ? $imagesUrls
                    : array_shift($imagesUrls);
            }
        }

        return $modelsData;
    }
}
